<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_notifications_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * In-app notification — tidak pakai email untuk saat ini.
     * Kolom `type` menentukan ikon & warna di bell notification frontend.
     * Kolom `data` JSON menyimpan konteks untuk deep link ke halaman terkait.
     *
     * Contoh data JSON:
     * { "job_id": "uuid-xxx", "project_id": "uuid-yyy" }
     * { "invoice_id": "uuid-zzz" }
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Tipe notifikasi — menentukan tampilan di frontend
            $table->string('type', 80);         // job_assigned | job_review | invoice_sent | dll
            $table->string('title', 200);
            $table->text('message');
            $table->json('data')->nullable();   // konteks untuk deep link
            $table->string('action_url', 500)->nullable(); // URL tujuan saat notif diklik

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamp('created_at')->useCurrent(); // append-only, tidak perlu updated_at

            $table->index('user_id');
            $table->index('is_read');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
