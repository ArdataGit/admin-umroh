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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_karyawan')->unique();
            $table->string('nik_karyawan')->unique();
            $table->string('nama_karyawan');
            $table->string('kontak_karyawan');
            $table->string('email_karyawan')->unique();
            $table->string('kabupaten_kota');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat_karyawan');
            $table->text('catatan_karyawan')->nullable();
            $table->string('foto_karyawan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
