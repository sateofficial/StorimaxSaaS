<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_portfolio_tags_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tag disimpan per baris (normalized), bukan JSON array.
     * Alasan: bisa difilter dengan index, bisa aggregate (popular tags), bisa search exact.
     * Misal: portfolio A punya 3 baris tag → "wedding", "outdoor", "2025"
     */
    public function up(): void
    {
        Schema::create('portfolio_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('portfolio_id')
                  ->constrained('portfolios')
                  ->cascadeOnDelete();

            $table->string('tag', 80);
            $table->timestamps();

            $table->unique(['portfolio_id', 'tag']); // satu tag tidak bisa duplikat per portfolio
            $table->index('tag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_tags');
    }
};
