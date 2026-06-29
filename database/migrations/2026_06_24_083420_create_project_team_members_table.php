<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_project_team_members_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table antara project_teams dan users.
     * Satu crew bisa masuk ke beberapa tim lintas project.
     * Unique constraint mencegah crew masuk dua kali ke tim yang sama.
     */
    public function up(): void
    {
        Schema::create('project_team_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_team_id')
                  ->constrained('project_teams')
                  ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['project_team_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_team_members');
    }
};
