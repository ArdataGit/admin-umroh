<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_layanan')->unique();
            $table->enum('jenis_layanan', ['Pesawat', 'Hotel', 'Visa', 'Transport', 'Handling', 'Tour', 'Layanan', 'Lainnya']);
            $table->string('nama_layanan');
            $table->enum('satuan_unit', ['Pcs', 'Set', 'Pack', 'Dus', 'Lot', 'Pax', 'Room', 'Seat']);
            $table->decimal('harga_modal', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->enum('status_layanan', ['Active', 'Non Active']);
            $table->text('catatan_layanan')->nullable();
            $table->string('foto_layanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
