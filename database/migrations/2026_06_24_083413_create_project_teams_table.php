<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_project_teams_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu project bisa punya beberapa tim (misal: Tim Foto, Tim Video, Tim Editing).
     * Setiap tim punya satu PIC dari crew.
     * Satu crew bisa jadi PIC di beberapa tim lintas project.
     */
    public function up(): void
    {
        Schema::create('project_teams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();
            $table->foreignUuid('pic_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();       // jika crew dihapus, PIC jadi null — tim tidak ikut terhapus

            $table->string('team_name', 100);               // misal: "Tim Foto", "Tim Video"
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->index('pic_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_teams');
    }
};
