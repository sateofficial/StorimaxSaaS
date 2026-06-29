<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_job_logs_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only log — tidak pernah ada UPDATE atau DELETE di tabel ini.
     * Dipakai untuk timeline progress job dan audit trail.
     * Hanya pakai created_at, tidak perlu updated_at.
     */
    public function up(): void
    {
        Schema::create('job_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_id')
                  ->constrained('jobs')
                  ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->text('note')->nullable();               // catatan opsional dari crew/admin

            $table->timestamp('created_at')->useCurrent();  // hanya created_at, log tidak bisa diedit

            $table->index('job_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_logs');
    }
};
