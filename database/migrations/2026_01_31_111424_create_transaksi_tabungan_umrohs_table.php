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
        Schema::create('transaksi_tabungan_umrohs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabungan_umroh_id')->constrained('tabungan_umrohs')->onDelete('cascade');
            $table->date('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['setoran', 'penarikan']);
            $table->decimal('nominal', 15, 2);
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
        Schema::dropIfExists('transaksi_tabungan_umrohs');
    }
};
