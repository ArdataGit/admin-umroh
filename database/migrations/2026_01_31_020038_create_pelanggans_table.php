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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelanggan')->unique();
            $table->string('nama_pelanggan');
            $table->string('kontak_pelanggan');
            $table->string('email_pelanggan')->unique();
            $table->string('kabupaten_kota');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('status_pelanggan', ['Active', 'Non Active']);
            $table->text('alamat_pelanggan');
            $table->text('catatan_pelanggan')->nullable();
            $table->string('foto_pelanggan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
