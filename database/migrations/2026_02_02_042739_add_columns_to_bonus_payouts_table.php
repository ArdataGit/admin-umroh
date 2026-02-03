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
        Schema::table('bonus_payouts', function (Blueprint $table) {
            $table->string('kode_transaksi')->unique()->after('id');
            $table->string('metode_pembayaran')->after('jumlah_bayar');
            $table->string('kode_referensi_mutasi')->nullable()->after('metode_pembayaran');
            $table->string('bukti_pembayaran')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonus_payouts', function (Blueprint $table) {
            $table->dropColumn(['kode_transaksi', 'metode_pembayaran', 'kode_referensi_mutasi', 'bukti_pembayaran']);
        });
    }
};
