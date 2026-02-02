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
        Schema::create('pemasukan_umums', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemasukan')->unique();
            $table->date('tanggal_pemasukan');
            $table->string('jenis_pemasukan'); // lainya
            $table->string('nama_pemasukan');
            $table->decimal('jumlah_pemasukan', 15, 2);
            $table->text('catatan_pemasukan')->nullable();
            $table->string('bukti_pemasukan')->nullable(); // File path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan_umums');
    }
};
