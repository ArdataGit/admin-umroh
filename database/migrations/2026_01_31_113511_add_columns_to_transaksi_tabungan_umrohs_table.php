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
        Schema::table('transaksi_tabungan_umrohs', function (Blueprint $table) {
            $table->string('kode_transaksi')->unique()->after('id');
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'Debit', 'QRIS', 'Other'])->after('nominal');
            $table->string('kode_referensi')->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_tabungan_umrohs', function (Blueprint $table) {
            //
        });
    }
};
