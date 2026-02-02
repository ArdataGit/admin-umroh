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
        Schema::create('maskapais', function (Blueprint $table) {
            $table->id();
            $table->string('kode_maskapai')->unique();
            $table->string('nama_maskapai');
            $table->string('rute_penerbangan'); // Direct, Transit
            $table->integer('lama_perjalanan'); // In hours
            $table->decimal('harga_tiket', 15, 2);
            $table->text('catatan_penerbangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maskapais');
    }
};
