<?php

namespace Tests\Feature\Admin;

use App\Enums\InvoiceStatus;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Models\Client;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Client $client;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $dept = Department::create(['name' => 'Finance', 'slug' => 'finance']);

        $this->admin = User::factory()->create([
            'department_id' => $dept->id,
            'name' => 'Admin Keuangan',
            'email' => 'finance@test.com',
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $clientUser = User::factory()->create([
            'department_id' => $dept->id,
            'name' => 'Client Inv',
            'email' => 'clientinv@test.com',
            'role' => UserRole::CLIENT,
            'is_active' => true,
        ]);

        $this->client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'PT Client Invoice',
            'contact_name' => 'Agus',
            'phone' => '081111111',
            'address' => 'Bandung',
        ]);

        $this->project = Project::create([
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'name' => 'Project Invoice',
            'code' => 'STX-2026-010',
            'status' => ProjectStatus::ACTIVE,
            'priority' => 'medium',
        ]);

        $this->actingAs($this->admin);
    }

    /** @test */
    public function it_can_list_invoices()
    {
        $invoice = $this->createDraftInvoice();

        $response = $this->get(route('admin.invoices.index'));

        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
    }

    /** @test */
    public function it_can_show_create_form()
    {
        $response = $this->get(route('admin.invoices.create'));

        $response->assertStatus(200);
        $response->assertSee('Project Invoice');
    }

    /** @test */
    public function it_can_store_invoice_with_items()
    {
        $response = $this->post(route('admin.invoices.store'), $this->withCsrf([
            'project_id' => $this->project->id,
            'invoice_date' => '2026-07-01',
            'session_date' => '2026-07-15',
            'due_date' => '2026-07-30',
            'pph_rate' => 2,
            'dp_amount' => 1500000,
            'bank_name' => 'BCA',
            'bank_account' => '0191040839',
            'bank_holder' => 'PT JALUR TENGAH KREASINDO',
            'payment_notes' => 'Bayar ya',
            'items' => [
                [
                    'service_name' => 'Video Shooting',
                    'description' => 'Shooting 1 hari',
                    'price' => 5000000,
                    'disc_percent' => 0,
                ],
                [
                    'service_name' => 'Editing',
                    'description' => 'Editing 3 hari',
                    'price' => 3000000,
                    'disc_percent' => 10,
                ],
            ],
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invoices', [
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'pph_rate' => 2,
            'status' => InvoiceStatus::DRAFT->value,
        ]);

        $invoice = Invoice::where('project_id', $this->project->id)->first();
        $this->assertEquals(7700000, (int) $invoice->subtotal);
        $this->assertEquals(154000, (int) $invoice->pph_amount);
        $this->assertEquals(7546000, (int) $invoice->total);
        $this->assertEquals(1500000, (int) $invoice->dp_amount);
        $this->assertEquals(6046000, (int) $invoice->remaining);
        $this->assertStringStartsWith('INV/STX/', $invoice->invoice_number);
        $this->assertCount(2, $invoice->items);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->post(route('admin.invoices.store'), $this->withCsrf([]));

        $response->assertSessionHasErrors(['project_id', 'invoice_date', 'pph_rate', 'items']);
    }

    /** @test */
    public function it_can_show_invoice_detail()
    {
        $invoice = $this->createDraftInvoice();

        $response = $this->get(route('admin.invoices.show', $invoice));

        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
    }

    /** @test */
    public function it_can_update_status_to_sent()
    {
        $invoice = $this->createDraftInvoice();

        $response = $this->patch(route('admin.invoices.update-status', $invoice), $this->withCsrf([
            'status' => 'sent',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::SENT->value,
        ]);
        $this->assertNotNull($invoice->fresh()->sent_at);
    }

    /** @test */
    public function it_can_update_status_to_dp_paid()
    {
        $invoice = $this->createDraftInvoice();

        $invoice->update(['status' => InvoiceStatus::SENT, 'sent_at' => now()]);

        $response = $this->patch(route('admin.invoices.update-status', $invoice), $this->withCsrf([
            'status' => 'dp_paid',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::DP_PAID->value, $invoice->status->value);
        $this->assertNotNull($invoice->dp_paid_at);
        $this->assertEquals($invoice->dp_amount, $invoice->dp_paid);
    }

    /** @test */
    public function it_can_update_status_to_paid()
    {
        $invoice = $this->createDraftInvoice();

        $invoice->update([
            'status' => InvoiceStatus::DP_PAID,
            'sent_at' => now(),
            'dp_paid_at' => now(),
            'dp_paid' => $invoice->dp_amount,
        ]);

        $response = $this->patch(route('admin.invoices.update-status', $invoice), $this->withCsrf([
            'status' => 'paid',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::PAID->value, $invoice->status->value);
        $this->assertNotNull($invoice->paid_at);
        $this->assertEquals($invoice->total, $invoice->dp_paid);
    }

    /** @test */
    public function it_validates_invoice_status_transition()
    {
        $invoice = $this->createDraftInvoice();

        $response = $this->patch(route('admin.invoices.update-status', $invoice), $this->withCsrf([
            'status' => 'invalid_status',
        ]));

        $response->assertSessionHasErrors(['status']);
    }

    /** @test */
    public function it_can_delete_an_invoice()
    {
        $invoice = $this->createDraftInvoice();

        $response = $this->delete(route('admin.invoices.destroy', $invoice), $this->withCsrf([]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($invoice);
    }

    /** @test */
    public function it_can_download_invoice_pdf()
    {
        $invoice = $this->createDraftInvoice();

        $response = $this->get(route('admin.invoices.pdf', $invoice));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function it_returns_empty_list_when_no_invoices()
    {
        $response = $this->get(route('admin.invoices.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_handle_zero_dp()
    {
        $response = $this->post(route('admin.invoices.store'), $this->withCsrf([
            'project_id' => $this->project->id,
            'invoice_date' => '2026-07-01',
            'pph_rate' => 0,
            'dp_amount' => 0,
            'items' => [
                [
                    'service_name' => 'Jasa Foto',
                    'price' => 2000000,
                    'disc_percent' => 0,
                ],
            ],
        ]));

        $response->assertRedirect();
        $invoice = Invoice::where('project_id', $this->project->id)
            ->latest()
            ->first();

        $this->assertEquals(0, (int) $invoice->pph_amount);
        $this->assertEquals(0, (int) $invoice->dp_amount);
        $this->assertEquals(2000000, (int) $invoice->total);
        $this->assertEquals(2000000, (int) $invoice->remaining);
    }

    /** @test */
    public function it_includes_items_in_invoice_creation()
    {
        $response = $this->post(route('admin.invoices.store'), $this->withCsrf([
            'project_id' => $this->project->id,
            'invoice_date' => '2026-07-01',
            'pph_rate' => 0,
            'items' => [
                [
                    'service_name' => 'Paket A',
                    'description' => 'Paket lengkap',
                    'price' => 1000000,
                    'disc_percent' => 20,
                ],
            ],
        ]));

        $response->assertRedirect();
        $invoice = Invoice::where('project_id', $this->project->id)
            ->latest()
            ->first();

        $this->assertCount(1, $invoice->items);
        $this->assertEquals('Paket A', $invoice->items->first()->service_name);
        $this->assertEquals(20, (int) $invoice->items->first()->disc_percent);
        $this->assertEquals(200000, (int) $invoice->items->first()->disc_amount);
        $this->assertEquals(800000, (int) $invoice->items->first()->total);
    }

    /**
     * Helper: Start session and return data with CSRF token.
     */
    private function withCsrf(array $data): array
    {
        $this->app['session']->start();
        return array_merge(['_token' => csrf_token()], $data);
    }

    private function createDraftInvoice(): Invoice
    {
        $invoice = Invoice::create([
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'invoice_number' => 'INV/STX/2026/001',
            'invoice_date' => '2026-07-01',
            'subtotal' => 5000000,
            'pph_rate' => 2,
            'pph_amount' => 100000,
            'total' => 4900000,
            'dp_amount' => 1000000,
            'dp_paid' => 0,
            'remaining' => 3900000,
            'status' => InvoiceStatus::DRAFT,
            'bank_name' => 'BCA',
            'bank_account' => '0191040839',
            'bank_holder' => 'PT JALUR TENGAH KREASINDO',
        ]);

        $invoice->items()->create([
            'service_name' => 'Video Shooting',
            'price' => 5000000,
            'disc_percent' => 0,
            'disc_amount' => 0,
            'total' => 5000000,
            'sort_order' => 0,
        ]);

        return $invoice;
    }
}
