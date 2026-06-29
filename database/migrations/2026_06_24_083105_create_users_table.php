<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_users_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
//
// CATATAN: Laravel 11 sudah punya default migration users_table.
// Hapus file default itu dulu, baru generate ulang dengan command di atas.
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id')
                  ->nullable()
                  ->constrained('departments')
                  ->nullOnDelete();

            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'atasan', 'crew', 'client'])->default('crew');
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role');
            $table->index('is_active');
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
