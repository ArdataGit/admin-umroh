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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique();
            $table->enum('jenis_tiket', ['Ekonomi', 'Bisnis']);
            $table->string('nama_tiket');
            $table->enum('satuan_unit', ['Pax']);
            $table->foreignId('maskapai_id')->constrained('maskapais')->onDelete('cascade');
            $table->string('kode_maskapai'); // Can be input by user
            $table->string('rute_tiket');
            $table->string('kode_pnr');
            $table->integer('jumlah_tiket');
            $table->date('tanggal_keberangkatan');
            $table->date('tanggal_kepulangan');
            $table->integer('jumlah_hari');
            $table->decimal('harga_modal', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->enum('status_tiket', ['active', 'non-active']);
            $table->string('kode_tiket_1')->nullable();
            $table->string('kode_tiket_2')->nullable();
            $table->string('kode_tiket_3')->nullable();
            $table->string('kode_tiket_4')->nullable();
            $table->text('catatan_tiket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
