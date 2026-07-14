<?php

namespace Tests\Feature\Admin;

use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $crew;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@test.com',
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $this->crew = User::factory()->create([
            'name' => 'Crew Test',
            'email' => 'crew@test.com',
            'role' => UserRole::CREW,
            'is_active' => true,
        ]);

        $clientUser = User::factory()->create([
            'name' => 'Client User',
            'email' => 'clientuser@test.com',
            'role' => UserRole::CLIENT,
            'is_active' => true,
        ]);

        $this->client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'PT Contoh',
            'contact_name' => 'Budi',
            'phone' => '08123456789',
            'address' => 'Jakarta',
        ]);

        $this->actingAs($this->admin);
    }

    /** @test */
    public function it_can_list_projects()
    {
        Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Project Test',
            'code' => 'STX-2026-001',
            'status' => ProjectStatus::DRAFT,
            'priority' => 'medium',
        ]);

        $response = $this->get(route('admin.projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Project Test');
        $response->assertSee('STX-2026-001');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        $response = $this->get(route('admin.projects.create'));

        $response->assertStatus(200);
        $response->assertSee('name="client_id"', false);
    }

    /** @test */
    public function it_can_store_a_new_project()
    {
        $response = $this->post(route('admin.projects.store'), $this->withCsrf([
            'client_id' => $this->client->id,
            'name' => 'Project Baru',
            'category' => 'Video',
            'description' => 'Testing',
            'priority' => 'high',
            'deadline' => '2026-08-01',
            'notes' => 'Test notes',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'name' => 'Project Baru',
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'category' => 'Video',
            'priority' => 'high',
        ]);

        $project = Project::where('name', 'Project Baru')->first();
        $this->assertNotNull($project->code);
        $this->assertStringStartsWith('STX-', $project->code);
        $this->assertEquals(ProjectStatus::DRAFT->value, $project->status->value);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->post(route('admin.projects.store'), $this->withCsrf([]));

        $response->assertSessionHasErrors(['client_id', 'name', 'priority']);
    }

    /** @test */
    public function it_can_show_a_project()
    {
        $project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Detail Project',
            'code' => 'STX-2026-002',
            'status' => ProjectStatus::ACTIVE,
            'priority' => 'medium',
        ]);

        $response = $this->get(route('admin.projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee('Detail Project');
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Edit Project',
            'code' => 'STX-2026-003',
            'status' => ProjectStatus::DRAFT,
            'priority' => 'low',
        ]);

        $response = $this->get(route('admin.projects.edit', $project));

        $response->assertStatus(200);
        $response->assertSee('Edit Project');
    }

    /** @test */
    public function it_can_update_a_project()
    {
        $project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Lama',
            'code' => 'STX-2026-004',
            'status' => ProjectStatus::DRAFT,
            'priority' => 'low',
        ]);

        $response = $this->put(route('admin.projects.update', $project), $this->withCsrf([
            'client_id' => $this->client->id,
            'name' => 'Updated Name',
            'category' => 'Fotografi',
            'description' => 'Updated',
            'priority' => 'urgent',
            'deadline' => '2026-09-01',
            'notes' => 'Updated notes',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Name',
            'category' => 'Fotografi',
            'priority' => 'urgent',
        ]);
    }

    /** @test */
    public function it_can_soft_delete_a_project()
    {
        $project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'To Delete',
            'code' => 'STX-2026-005',
            'status' => ProjectStatus::DRAFT,
            'priority' => 'low',
        ]);

        $response = $this->delete(route('admin.projects.destroy', $project), $this->withCsrf([]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($project);
    }

    /** @test */
    public function it_can_update_project_status()
    {
        $project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Status Test',
            'code' => 'STX-2026-006',
            'status' => ProjectStatus::DRAFT,
            'priority' => 'medium',
        ]);

        $response = $this->patch(route('admin.projects.update-status', $project), $this->withCsrf([
            'status' => 'active',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => ProjectStatus::ACTIVE->value,
        ]);
    }

    /** @test */
    public function it_validates_status_transition()
    {
        $project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Invalid Status',
            'code' => 'STX-2026-007',
            'status' => ProjectStatus::DRAFT,
            'priority' => 'medium',
        ]);

        $response = $this->patch(route('admin.projects.update-status', $project), $this->withCsrf([
            'status' => 'invalid_status',
        ]));

        $response->assertSessionHasErrors(['status']);
    }

    /** @test */
    public function it_returns_empty_list_when_no_projects()
    {
        $response = $this->get(route('admin.projects.index'));

        $response->assertStatus(200);
    }

    /**
     * Helper: Start session and return data with CSRF token.
     * This ensures the token matches what the CSRF middleware expects.
     */
    private function withCsrf(array $data): array
    {
        $this->app['session']->start();
        return array_merge(['_token' => csrf_token()], $data);
    }
}
