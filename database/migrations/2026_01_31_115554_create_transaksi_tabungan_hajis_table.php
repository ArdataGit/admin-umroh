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
        Schema::create('transaksi_tabungan_hajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabungan_haji_id')->constrained('tabungan_hajis')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['setoran', 'penarikan']);
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'Debit', 'QRIS', 'Other']);
            $table->string('status_setoran')->default('checked');
            $table->string('kode_referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('bukti_transaksi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_tabungan_hajis');
    }
};
