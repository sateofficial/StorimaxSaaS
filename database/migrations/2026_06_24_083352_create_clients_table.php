<?php

// ============================================================
// CARA PAKAI:
// 1. php artisan make:migration create_clients_table
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
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Sesuai field form invoice Storimax
            $table->string('company_name', 150)->nullable();    // nama perusahaan/instansi
            $table->string('contact_name', 150);                // "Nama Client"
            $table->string('phone', 20)->nullable();            // "Kontak"
            $table->string('instagram', 100)->nullable();       // "Akun Instagram"
            $table->text('address')->nullable();                // "Alamat/Instansi"
            $table->text('notes')->nullable();                  // catatan internal

            $table->timestamps();
            $table->softDeletes();

            $table->index('contact_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
