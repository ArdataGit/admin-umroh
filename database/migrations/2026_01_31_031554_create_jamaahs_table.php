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
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jamaah')->unique();
            $table->string('nik_jamaah');
            $table->string('nama_jamaah');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('kontak_jamaah');
            $table->string('email_jamaah')->nullable();
            $table->string('kecamatan');
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->text('alamat_jamaah');
            $table->text('alamat_lengkap');
            $table->text('catatan_jamaah')->nullable();
            
            // Passport Data
            $table->string('nama_paspor')->nullable();
            $table->string('nomor_paspor')->nullable();
            $table->string('kantor_imigrasi')->nullable();
            $table->date('tgl_paspor_aktif')->nullable();
            $table->date('tgl_paspor_expired')->nullable();
            
            // Photos
            $table->string('foto_jamaah')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('foto_paspor_1')->nullable();
            $table->string('foto_paspor_2')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
