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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('kode_agent')->unique();
            $table->string('nik_agent')->unique();
            $table->string('nama_agent');
            $table->string('kontak_agent');
            $table->string('email_agent')->unique();
            $table->string('kabupaten_kota');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('status_agent', ['Active', 'Non Active']);
            $table->decimal('komisi_paket_umroh', 15, 2);
            $table->decimal('komisi_paket_haji', 15, 2);
            $table->text('alamat_agent');
            $table->text('catatan_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
