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
        Schema::create('surat_izin_cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->foreignId('keberangkatan_umroh_id')->constrained('keberangkatan_umrohs')->onDelete('cascade');
            $table->string('nomor_dokumen');
            $table->string('kantor_instansi');
            $table->string('nik_instansi')->nullable(); // Made nullable just in case, or required as per request implies input
            $table->string('jabatan_instansi');
            $table->string('nama_ayah');
            $table->string('nama_kakek');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_izin_cutis');
    }
};
