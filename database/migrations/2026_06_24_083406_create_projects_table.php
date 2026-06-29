<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_projects_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')
                  ->constrained('clients')
                  ->restrictOnDelete();   // tidak bisa hapus client jika masih ada project aktif
            $table->foreignUuid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('name', 200);
            $table->string('code', 30)->unique();           // misal: STX-2025-001
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();    // foto, video, konten, branding, dll
            $table->enum('status', [
                'draft',
                'active',
                'review',
                'done',
                'archived',
            ])->default('draft');
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'urgent',
            ])->default('medium');
            $table->date('deadline')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('priority');
            $table->index('deadline');
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
