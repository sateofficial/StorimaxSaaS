<?php

namespace Tests\Feature\Admin;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Models\Client;
use App\Models\Department;
use App\Models\Job;
use App\Models\Project;
use App\Models\ProjectTeam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $crew;
    private Client $client;
    private Project $project;
    private ProjectTeam $team;

    protected function setUp(): void
    {
        parent::setUp();

        $dept = Department::create(['name' => 'Production', 'slug' => 'production']);

        $this->admin = User::factory()->create([
            'department_id' => $dept->id,
            'name' => 'Admin Produksi',
            'email' => 'produksi@test.com',
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $this->crew = User::factory()->create([
            'department_id' => $dept->id,
            'name' => 'Crew Jhon',
            'email' => 'jhon@test.com',
            'role' => UserRole::CREW,
            'is_active' => true,
        ]);

        $clientUser = User::factory()->create([
            'department_id' => $dept->id,
            'name' => 'Client Job',
            'email' => 'clientjob@test.com',
            'role' => UserRole::CLIENT,
            'is_active' => true,
        ]);

        $this->client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'PT Job Test',
            'contact_name' => 'Client Job',
            'phone' => '081234567',
            'address' => 'Jakarta',
        ]);

        $this->project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Project Job',
            'code' => 'STX-2026-020',
            'status' => ProjectStatus::ACTIVE,
            'priority' => 'medium',
        ]);

        $this->team = ProjectTeam::create([
            'project_id' => $this->project->id,
            'team_name' => 'Tim Kreatif',
            'pic_user_id' => $this->crew->id,
        ]);

        $this->actingAs($this->admin);
    }

    /** @test */
    public function it_can_list_all_jobs()
    {
        $job = $this->createJob();

        $response = $this->get(route('admin.jobs.index'));

        $response->assertStatus(200);
        $response->assertSee($job->title);
    }

    /** @test */
    public function it_can_show_create_form()
    {
        $response = $this->get(route('admin.projects.jobs.create', $this->project));

        $response->assertStatus(200);
        $response->assertSee('Tim Kreatif');
        $response->assertSee('Crew Jhon');
    }

    /** @test */
    public function it_can_store_a_job()
    {
        $response = $this->post(route('admin.projects.jobs.store', $this->project), $this->withCsrf([
            'title' => 'Job Baru',
            'description' => 'Deskripsi job baru',
            'project_team_id' => $this->team->id,
            'assigned_to' => $this->crew->id,
            'priority' => 'high',
            'deadline' => '2026-08-15',
            'notes' => 'Catatan penting',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'project_id' => $this->project->id,
            'title' => 'Job Baru',
            'assigned_to' => $this->crew->id,
            'project_team_id' => $this->team->id,
            'status' => JobStatus::TODO->value,
            'priority' => JobPriority::HIGH->value,
            'created_by' => $this->admin->id,
        ]);
    }

    /** @test */
    public function it_can_store_job_without_assignee()
    {
        $response = $this->post(route('admin.projects.jobs.store', $this->project), $this->withCsrf([
            'title' => 'Job Unassigned',
            'priority' => 'low',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'project_id' => $this->project->id,
            'title' => 'Job Unassigned',
            'status' => JobStatus::TODO->value,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->post(route('admin.projects.jobs.store', $this->project), $this->withCsrf([]));

        $response->assertSessionHasErrors(['title', 'priority']);
    }

    /** @test */
    public function it_can_show_job_detail()
    {
        $job = $this->createJob();

        $response = $this->get(route('admin.jobs.show', $job));

        $response->assertStatus(200);
        $response->assertSee($job->title);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $job = $this->createJob();

        $response = $this->get(route('admin.jobs.edit', $job));

        $response->assertStatus(200);
        $response->assertSee($job->title);
    }

    /** @test */
    public function it_can_update_a_job()
    {
        $job = $this->createJob();

        $response = $this->put(route('admin.jobs.update', $job), $this->withCsrf([
            'title' => 'Updated Title',
            'priority' => 'urgent',
            'assigned_to' => $this->crew->id,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => 'Updated Title',
            'priority' => JobPriority::URGENT->value,
        ]);
    }

    /** @test */
    public function it_can_delete_a_job()
    {
        $job = $this->createJob();

        $response = $this->delete(route('admin.jobs.destroy', $job), $this->withCsrf([]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($job);
    }

    /** @test */
    public function it_can_update_job_status_to_in_progress()
    {
        $job = $this->createJob();

        $response = $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf([
            'status' => 'inprogress',
            'note' => 'Mulai ngerjakan',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals(JobStatus::INPROGRESS->value, $job->status->value);
        $this->assertNotNull($job->started_at);

        $this->assertDatabaseHas('job_logs', [
            'job_id' => $job->id,
            'user_id' => $this->admin->id,
            'old_status' => JobStatus::TODO->value,
            'new_status' => JobStatus::INPROGRESS->value,
            'note' => 'Mulai ngerjakan',
        ]);
    }

    /** @test */
    public function it_can_update_job_status_through_full_cycle()
    {
        $job = $this->createJob();

        $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf(['status' => 'inprogress']));
        $this->assertEquals(JobStatus::INPROGRESS->value, $job->fresh()->status->value);

        $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf(['status' => 'review']));
        $this->assertEquals(JobStatus::REVIEW->value, $job->fresh()->status->value);

        $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf(['status' => 'done']));
        $job->refresh();
        $this->assertEquals(JobStatus::DONE->value, $job->status->value);
        $this->assertNotNull($job->completed_at);

        $this->assertEquals(3, $job->logs()->count());
    }

    /** @test */
    public function it_logs_every_status_change()
    {
        $job = $this->createJob();

        $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf([
            'status' => 'inprogress',
            'note' => 'First update',
        ]));

        $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf([
            'status' => 'done',
            'note' => 'Completed',
        ]));

        $logs = $job->logs()->orderBy('id')->get();
        $this->assertCount(2, $logs);

        $this->assertEquals(JobStatus::TODO->value, $logs[0]->old_status);
        $this->assertEquals(JobStatus::INPROGRESS->value, $logs[0]->new_status);
        $this->assertEquals('First update', $logs[0]->note);

        $this->assertEquals(JobStatus::INPROGRESS->value, $logs[1]->old_status);
        $this->assertEquals(JobStatus::DONE->value, $logs[1]->new_status);
        $this->assertEquals('Completed', $logs[1]->note);
    }

    /** @test */
    public function it_validates_job_status_transition()
    {
        $job = $this->createJob();

        $response = $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf([
            'status' => 'invalid_status',
        ]));

        $response->assertSessionHasErrors(['status']);
    }

    /** @test */
    public function show_returns_404_for_non_existent_job()
    {
        $response = $this->get(route('admin.jobs.show', 'non-existent-uuid'));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_empty_list_when_no_jobs()
    {
        $response = $this->get(route('admin.jobs.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_handle_job_without_team()
    {
        $job = Job::create([
            'project_id' => $this->project->id,
            'created_by' => $this->admin->id,
            'title' => 'Job Without Team',
            'status' => JobStatus::TODO,
            'priority' => JobPriority::MEDIUM,
        ]);

        $response = $this->get(route('admin.jobs.show', $job));

        $response->assertStatus(200);
        $response->assertSee('Job Without Team');
    }

    /** @test */
    public function it_can_update_job_with_note()
    {
        $job = $this->createJob();

        $response = $this->patch(route('admin.jobs.update-status', $job), $this->withCsrf([
            'status' => 'inprogress',
            'note' => 'Mulai pengerjaan dengan tim',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_logs', [
            'job_id' => $job->id,
            'note' => 'Mulai pengerjaan dengan tim',
        ]);
    }

    /**
     * Helper: Start session and return data with CSRF token.
     */
    private function withCsrf(array $data): array
    {
        $this->app['session']->start();
        return array_merge(['_token' => csrf_token()], $data);
    }

    private function createJob(): Job
    {
        return Job::create([
            'project_id' => $this->project->id,
            'project_team_id' => $this->team->id,
            'assigned_to' => $this->crew->id,
            'created_by' => $this->admin->id,
            'title' => 'Job Utama',
            'description' => 'Deskripsi job utama',
            'status' => JobStatus::TODO,
            'priority' => JobPriority::HIGH,
            'deadline' => '2026-08-20',
            'notes' => 'Note penting',
        ]);
    }
}
