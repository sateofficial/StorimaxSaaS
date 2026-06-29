<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_portfolios_table
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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')
                  ->nullable()
                  ->constrained('projects')
                  ->nullOnDelete();       // portofolio tetap ada walau project dihapus
            $table->foreignUuid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('thumbnail_path', 500)->nullable();
            $table->string('category', 100)->nullable();    // foto, video, branding, dll

            // Toggle visibilitas ke client portal
            $table->boolean('is_public')->default(false);
            $table->timestamp('published_at')->nullable();  // kapan pertama kali dipublikasikan

            $table->timestamps();
            $table->softDeletes();

            $table->index('is_public');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
