<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_activity_logs_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Audit trail seluruh sistem — penting untuk agency 30+ orang.
     * Diisi otomatis oleh Middleware LogActivity.php untuk setiap aksi penting.
     * Atasan bisa lihat log ini dari dashboard tanpa perlu tanya siapa yang ubah apa.
     *
     * Contoh baris log:
     *   module  : "invoice"
     *   action  : "status_changed"
     *   payload : { "from": "sent", "to": "dp_paid", "invoice_number": "INV/STX/2025/001" }
     *
     *   module  : "job"
     *   action  : "assigned"
     *   payload : { "job_id": "uuid", "assigned_to": "Budi", "by": "Admin" }
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                  ->nullable()                  // nullable: bisa log dari sistem/console
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('module', 80);       // project | job | invoice | portfolio | user | dll
            $table->string('action', 80);       // created | updated | deleted | status_changed | dll
            $table->json('payload')->nullable(); // data konteks aksi (before/after, ID terkait, dll)
            $table->string('ip_address', 45)->nullable(); // IPv4 atau IPv6
            $table->string('user_agent', 500)->nullable();

            $table->timestamp('created_at')->useCurrent(); // append-only, tidak ada updated_at

            $table->index('user_id');
            $table->index('module');
            $table->index('action');
            $table->index('created_at');
            $table->index(['module', 'action']); // composite index untuk filter gabungan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
