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
        Schema::table('pembelian_produks', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 15, 2)->default(0)->after('total_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_produks', function (Blueprint $table) {
            $table->dropColumn('jumlah_bayar');
        });
    }
};
