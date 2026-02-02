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
        Schema::create('pengeluaran_umrohs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keberangkatan_umroh_id')->constrained('keberangkatan_umrohs')->onDelete('cascade');
            $table->string('kode_pengeluaran')->unique();
            $table->date('tanggal_pengeluaran');
            $table->string('jenis_pengeluaran'); // visa_umroh, siskopatuh, etc.
            $table->string('nama_pengeluaran');
            $table->decimal('jumlah_pengeluaran', 15, 2);
            $table->text('catatan_pengeluaran')->nullable();
            $table->string('bukti_pengeluaran')->nullable(); // File path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_umrohs');
    }
};
