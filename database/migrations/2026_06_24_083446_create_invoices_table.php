<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_invoices_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Struktur invoice disesuaikan dengan template Storimax:
     *   Header  : Nama Client, Kontak, Akun Instagram, Alamat/Instansi, Tgl Sesi
     *   Rincian : per item (lihat invoice_items)
     *   Summary : PPH 2%, Total, DP, Pelunasan
     *   Footer  : Metode Pembayaran, info rekening, catatan
     *
     * Scalability note — saat ini 1 invoice = 1 project (project_id NOT NULL).
     * Untuk mendukung multi-project di masa depan tanpa ubah tabel lain:
     *   1. Buat tabel pivot `invoice_projects`
     *   2. Jadikan project_id di sini nullable via migration baru
     *   3. Tidak ada perubahan pada tabel lain
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')
                  ->constrained('projects')
                  ->restrictOnDelete();
            $table->foreignUuid('client_id')
                  ->constrained('clients')
                  ->restrictOnDelete();
            $table->foreignUuid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();

            // ── Identitas invoice ─────────────────────────────────
            $table->string('invoice_number', 50)->unique();     // INV/STX/2025/001
            $table->date('invoice_date');
            $table->date('session_date')->nullable();           // "Tgl Sesi" di form Storimax
            $table->date('due_date')->nullable();               // batas waktu pelunasan

            // ── Kalkulasi keuangan (IDR, 2 desimal) ──────────────
            $table->decimal('subtotal', 15, 2)->default(0);    // total sebelum PPH
            $table->decimal('pph_rate', 5, 2)->default(2.00);  // persentase PPH, default 2%
            $table->decimal('pph_amount', 15, 2)->default(0);  // nominal PPH = subtotal × pph_rate / 100
            $table->decimal('total', 15, 2)->default(0);       // subtotal - pph_amount
            $table->decimal('dp_amount', 15, 2)->default(0);   // nominal DP yang disepakati
            $table->decimal('dp_paid', 15, 2)->default(0);     // DP yang sudah benar-benar diterima
            $table->decimal('remaining', 15, 2)->default(0);   // "Pelunasan" = total - dp_paid

            // ── Status alur invoice ───────────────────────────────
            $table->enum('status', [
                'draft',    // baru dibuat, belum dikirim ke client
                'sent',     // sudah dikirim, menunggu pembayaran
                'dp_paid',  // DP sudah masuk, menunggu pelunasan
                'paid',     // lunas penuh
                'overdue',  // melewati due_date, belum lunas
            ])->default('draft');

            // ── Info pembayaran (bisa override dari config/storimax.php) ─
            $table->string('bank_name', 100)->nullable();       // misal: BCA
            $table->string('bank_account', 50)->nullable();     // misal: 0191040839
            $table->string('bank_holder', 150)->nullable();     // misal: PT JALUR TENGAH KREASINDO
            $table->text('payment_notes')->nullable();          // catatan di footer PDF invoice
            $table->text('internal_notes')->nullable();         // catatan internal, tidak muncul di PDF

            // ── Tracking waktu pembayaran ─────────────────────────
            $table->timestamp('sent_at')->nullable();           // kapan invoice dikirim ke client
            $table->timestamp('dp_paid_at')->nullable();        // kapan DP diterima
            $table->timestamp('paid_at')->nullable();           // kapan lunas

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('invoice_date');
            $table->index('due_date');
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
