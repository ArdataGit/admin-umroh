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
        Schema::create('surat_rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->foreignId('keberangkatan_umroh_id')->constrained('keberangkatan_umrohs')->onDelete('cascade');
            $table->string('nomor_dokumen');
            $table->string('kantor_imigrasi');
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
        Schema::dropIfExists('surat_rekomendasis');
    }
};
