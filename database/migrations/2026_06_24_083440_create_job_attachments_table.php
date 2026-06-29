<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_job_attachments_table
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
        Schema::create('job_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_id')
                  ->constrained('jobs')
                  ->cascadeOnDelete();
            $table->foreignUuid('uploaded_by')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('file_name', 255);               // nama asli file saat upload
            $table->string('file_path', 500);               // path relatif di storage/app/public
            $table->string('file_type', 100)->nullable();   // mime type: image/jpeg, video/mp4, dll
            $table->unsignedBigInteger('file_size')->nullable(); // ukuran dalam bytes
            $table->enum('category', [
                'brief',        // brief / referensi dari admin ke crew
                'deliverable',  // hasil kerja dari crew
                'revision',     // file revisi
                'other',
            ])->default('other');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('job_id');
            $table->index('uploaded_by');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_attachments');
    }
};
