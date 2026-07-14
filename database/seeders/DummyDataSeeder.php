<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Job;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('email', 'admin@storimax.id')->first();
        $rizky   = Client::where('contact_name', 'Rizky Pratama')->first();
        $dewi    = Client::where('contact_name', 'Dewi Sartika')->first();
        $budi    = User::where('email', 'budi@storimax.id')->first();
        $sari    = User::where('email', 'sari@storimax.id')->first();
        $andi    = User::where('email', 'andi@storimax.id')->first();

        if (!$admin || !$rizky || !$dewi || !$budi || !$sari || !$andi) {
            $this->command->warn('Data referensi tidak lengkap. Jalankan DatabaseSeeder dulu.');
            return;
        }

        $existingCodes = Project::pluck('code')->toArray();

        // ═══════════════════════════════════════════════════════
        // PROJECT 2 — Branding Paket UMKM (Dewi)
        // ═══════════════════════════════════════════════════════
        if (!in_array('STX-2026-002', $existingCodes)) {
            $p2 = Project::create([
                'client_id'   => $dewi->id,
                'created_by'  => $admin->id,
                'name'        => 'Branding Paket UMKM',
                'code'        => 'STX-2026-002',
                'description' => 'Paket branding lengkap untuk UMKM binaan — logo, feed Instagram, konten promosi.',
                'category'    => 'Branding',
                'status'      => ProjectStatus::ACTIVE,
                'priority'    => JobPriority::HIGH,
                'deadline'    => now()->addDays(14),
                'notes'       => 'Budget terbatas tapi prioritas tinggi karena potensi repeat order.',
            ]);

            // Jobs
            Job::create([
                'project_id'  => $p2->id,
                'assigned_to' => $andi->id,
                'created_by'  => $admin->id,
                'title'       => 'Desain Logo UMKM',
                'description' => 'Buat 3 opsi logo untuk UMKM binaan dengan tema lokal.',
                'status'      => JobStatus::DONE,
                'priority'    => JobPriority::HIGH,
                'deadline'    => now()->addDays(5),
                'started_at'  => now()->subDays(3),
                'completed_at'=> now()->subDay(),
            ]);
            Job::create([
                'project_id'  => $p2->id,
                'assigned_to' => $sari->id,
                'created_by'  => $admin->id,
                'title'       => 'Konten Feed Instagram (10 slide)',
                'description' => 'Buat konten feed Instagram untuk 2 minggu: 5 edukasi, 3 promosi, 2 testimonials.',
                'status'      => JobStatus::INPROGRESS,
                'priority'    => JobPriority::MEDIUM,
                'deadline'    => now()->addDays(10),
                'started_at'  => now()->subDay(),
            ]);
            Job::create([
                'project_id'  => $p2->id,
                'assigned_to' => $andi->id,
                'created_by'  => $admin->id,
                'title'       => 'Video Reels Promosi 15 detik',
                'description' => 'Buat 3 video reels untuk IG/TikTok promosi UMKM.',
                'status'      => JobStatus::TODO,
                'priority'    => JobPriority::MEDIUM,
                'deadline'    => now()->addDays(14),
            ]);

            // Invoice
            $inv2 = Invoice::create([
                'project_id'     => $p2->id,
                'client_id'      => $dewi->id,
                'created_by'     => $admin->id,
                'invoice_number' => 'INV/STX/2026/002',
                'invoice_date'   => now()->subDays(2),
                'session_date'   => now()->subDays(2),
                'due_date'       => now()->addDays(28),
                'subtotal'       => 3500000,
                'pph_rate'       => 0,
                'pph_amount'     => 0,
                'total'          => 3500000,
                'dp_amount'      => 1500000,
                'dp_paid'        => 1500000,
                'remaining'      => 2000000,
                'status'         => InvoiceStatus::DP_PAID,
                'bank_name'      => 'BCA',
                'bank_account'   => '0191040839',
                'bank_holder'    => 'PT JALUR TENGAH KREASINDO',
                'sent_at'        => now()->subDays(2),
                'dp_paid_at'     => now()->subDay(),
            ]);
            InvoiceItem::create([
                'invoice_id'   => $inv2->id,
                'service_name' => 'Paket Branding UMKM',
                'description'  => 'Logo + 10 Feed IG + 3 Reels',
                'price'        => 3500000,
                'disc_percent' => 0,
                'disc_amount'  => 0,
                'total'        => 3500000,
                'sort_order'   => 1,
            ]);
        }

        // ═══════════════════════════════════════════════════════
        // PROJECT 3 — Live Streaming Wedding (Rizky)
        // ═══════════════════════════════════════════════════════
        if (!in_array('STX-2026-003', $existingCodes)) {
            $p3 = Project::create([
                'client_id'   => $rizky->id,
                'created_by'  => $admin->id,
                'name'        => 'Live Streaming Wedding',
                'code'        => 'STX-2026-003',
                'description' => 'Live streaming & dokumentasi pernikahan dengan multi-camera setup.',
                'category'    => 'Video',
                'status'      => ProjectStatus::REVIEW,
                'priority'    => JobPriority::URGENT,
                'deadline'    => now()->addDays(3),
                'notes'       => 'H-3, mohon dikejar. Client request tambahan slideshow.',
            ]);

            // Jobs
            Job::create([
                'project_id'  => $p3->id,
                'assigned_to' => $budi->id,
                'created_by'  => $admin->id,
                'title'       => 'Setup Camera & Lighting',
                'description' => 'Siapkan 3 camera Sony A7IV + lighting portable untuk venue indoor.',
                'status'      => JobStatus::DONE,
                'priority'    => JobPriority::URGENT,
                'deadline'    => now()->addDays(2),
                'started_at'  => now()->subDays(3),
                'completed_at'=> now()->subDays(2),
            ]);
            Job::create([
                'project_id'  => $p3->id,
                'assigned_to' => $sari->id,
                'created_by'  => $admin->id,
                'title'       => 'Live Streaming OBS Setup',
                'description' => 'Setup OBS untuk multi-camera switching + stream ke YouTube.',
                'status'      => JobStatus::DONE,
                'priority'    => JobPriority::URGENT,
                'deadline'    => now()->addDays(1),
                'started_at'  => now()->subDays(3),
                'completed_at'=> now()->subDay(),
            ]);
            Job::create([
                'project_id'  => $p3->id,
                'assigned_to' => $andi->id,
                'created_by'  => $admin->id,
                'title'       => 'Edit Highlight Wedding 3-5 Menit',
                'description' => 'Rangkuman momen penting dalam video 3-5 menit dengan musik + color grading.',
                'status'      => JobStatus::REVIEW,
                'priority'    => JobPriority::HIGH,
                'deadline'    => now()->addDays(5),
                'started_at'  => now()->subDay(),
            ]);

            // Invoice
            $inv3 = Invoice::create([
                'project_id'     => $p3->id,
                'client_id'      => $rizky->id,
                'created_by'     => $admin->id,
                'invoice_number' => 'INV/STX/2026/003',
                'invoice_date'   => now()->subDays(5),
                'session_date'   => now()->subDays(5),
                'due_date'       => now()->addDays(25),
                'subtotal'       => 12000000,
                'pph_rate'       => 2,
                'pph_amount'     => 240000,
                'total'          => 12240000,
                'dp_amount'      => 5000000,
                'dp_paid'        => 0,
                'remaining'      => 7240000,
                'status'         => InvoiceStatus::SENT,
                'bank_name'      => 'BCA',
                'bank_account'   => '0191040839',
                'bank_holder'    => 'PT JALUR TENGAH KREASINDO',
                'payment_notes'  => 'Pembayaran DP minimal 50% sebelum hari H.',
                'sent_at'        => now()->subDays(5),
            ]);
            InvoiceItem::create([
                'invoice_id'   => $inv3->id,
                'service_name' => 'Live Streaming Wedding',
                'description'  => 'Multi-camera 3 unit + OBS + Streaming YouTube',
                'price'        => 8000000,
                'disc_percent' => 0,
                'disc_amount'  => 0,
                'total'        => 8000000,
                'sort_order'   => 1,
            ]);
            InvoiceItem::create([
                'invoice_id'   => $inv3->id,
                'service_name' => 'Video Highlight',
                'description'  => 'Edit highlight 3-5 menit + color grading',
                'price'        => 4000000,
                'disc_percent' => 0,
                'disc_amount'  => 0,
                'total'        => 4000000,
                'sort_order'   => 2,
            ]);
        }

        // ═══════════════════════════════════════════════════════
        // PROJECT 4 — Fotografi Katalog Produk (Dewi)
        // ═══════════════════════════════════════════════════════
        if (!in_array('STX-2026-004', $existingCodes)) {
            $p4 = Project::create([
                'client_id'   => $dewi->id,
                'created_by'  => $admin->id,
                'name'        => 'Fotografi Katalog Produk',
                'code'        => 'STX-2026-004',
                'description' => 'Fotografi 50 item produk fashion untuk katalog online & marketplace.',
                'category'    => 'Fotografi',
                'status'      => ProjectStatus::DONE,
                'priority'    => JobPriority::MEDIUM,
                'deadline'    => now()->subDays(5),
            ]);

            // Jobs
            Job::create([
                'project_id'  => $p4->id,
                'assigned_to' => $budi->id,
                'created_by'  => $admin->id,
                'title'       => 'Foto 50 item produk',
                'description' => 'Flat lay + model shot untuk 50 item fashion.',
                'status'      => JobStatus::DONE,
                'priority'    => JobPriority::MEDIUM,
                'deadline'    => now()->subDays(7),
                'started_at'  => now()->subDays(14),
                'completed_at'=> now()->subDays(6),
            ]);

            // Invoice
            $inv4 = Invoice::create([
                'project_id'     => $p4->id,
                'client_id'      => $dewi->id,
                'created_by'     => $admin->id,
                'invoice_number' => 'INV/STX/2026/004',
                'invoice_date'   => now()->subDays(10),
                'session_date'   => now()->subDays(14),
                'due_date'       => now()->addDays(20),
                'subtotal'       => 7500000,
                'pph_rate'       => 2,
                'pph_amount'     => 150000,
                'total'          => 7650000,
                'dp_amount'      => 3000000,
                'dp_paid'        => 3000000,
                'remaining'      => 4650000,
                'status'         => InvoiceStatus::DP_PAID,
                'bank_name'      => 'BCA',
                'bank_account'   => '0191040839',
                'bank_holder'    => 'PT JALUR TENGAH KREASINDO',
                'sent_at'        => now()->subDays(10),
                'dp_paid_at'     => now()->subDays(8),
            ]);
            InvoiceItem::create([
                'invoice_id'   => $inv4->id,
                'service_name' => 'Paket Foto Katalog 50 Item',
                'description'  => 'Flat lay + model shot, editing dasar, hak pakai 50 foto',
                'price'        => 7500000,
                'disc_percent' => 0,
                'disc_amount'  => 0,
                'total'        => 7500000,
                'sort_order'   => 1,
            ]);
        }

        $this->command->info('✅ Data dummy berhasil ditambahkan!');
    }
}
