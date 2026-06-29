<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_invoice_items_table
// 2. Buka file yang digenerate di database/migrations/
// 3. Ganti isi up() dan down() dengan kode di bawah ini
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Line items invoice — sesuai kolom tabel Rincian di template Storimax:
     * | Jenis Layanan | Deskripsi | Harga | Disc | Total Disc | Total |
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')
                  ->constrained('invoices')
                  ->cascadeOnDelete();        // hapus invoice → hapus semua item-nya

            $table->string('service_name', 200);                    // "Jenis Layanan"
            $table->text('description')->nullable();                // "Deskripsi"
            $table->decimal('price', 15, 2)->default(0);           // "Harga"
            $table->decimal('disc_percent', 5, 2)->default(0);     // "Disc" dalam persen
            $table->decimal('disc_amount', 15, 2)->default(0);     // "Total Disc" = price × disc% / 100
            $table->decimal('total', 15, 2)->default(0);           // "Total" = price - disc_amount
            $table->unsignedTinyInteger('sort_order')->default(0); // urutan tampil di invoice PDF

            $table->timestamps();

            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
