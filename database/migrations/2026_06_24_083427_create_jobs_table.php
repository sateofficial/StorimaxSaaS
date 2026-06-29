<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_jobs_table
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
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();
            $table->foreignUuid('project_team_id')
                  ->nullable()
                  ->constrained('project_teams')
                  ->nullOnDelete();
            $table->foreignUuid('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();       // job tidak terhapus jika crew dihapus
            $table->foreignUuid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('status', [
                'todo',
                'inprogress',
                'review',
                'done',
            ])->default('todo');
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'urgent',
            ])->default('medium');
            $table->date('deadline')->nullable();
            $table->timestamp('started_at')->nullable();    // diisi otomatis saat status → inprogress
            $table->timestamp('completed_at')->nullable();  // diisi otomatis saat status → done
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('priority');
            $table->index('assigned_to');
            $table->index('project_id');
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
