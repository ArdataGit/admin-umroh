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
        Schema::create('pembayaran_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_layanan_id')->constrained('transaksi_layanans')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal_pembayaran');
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->string('metode_pembayaran');
            $table->string('status_pembayaran')->default('pending');
            $table->string('kode_referensi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_layanans');
    }
};
