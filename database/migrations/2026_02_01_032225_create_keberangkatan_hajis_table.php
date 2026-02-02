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
        Schema::create('keberangkatan_hajis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_keberangkatan')->unique();
            $table->foreignId('paket_haji_id')->constrained('paket_hajis')->onDelete('cascade');
            $table->string('nama_keberangkatan');
            $table->date('tanggal_keberangkatan');
            $table->integer('jumlah_hari');
            $table->integer('kuota_jamaah');
            $table->enum('status_keberangkatan', ['active', 'completed'])->default('active');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keberangkatan_hajis');
    }
};
