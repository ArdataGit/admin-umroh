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
        Schema::table('pembayaran_tikets', function (Blueprint $table) {
            $table->string('kurs')->default('IDR')->after('jumlah_pembayaran');
            $table->decimal('kurs_asing', 20, 2)->default(0)->after('kurs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_tikets', function (Blueprint $table) {
            $table->dropColumn(['kurs', 'kurs_asing']);
        });
    }
};
