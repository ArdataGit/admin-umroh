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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hotel')->unique();
            $table->string('nama_hotel');
            $table->string('lokasi_hotel');
            $table->string('kontak_hotel');
            $table->string('email_hotel');
            $table->integer('rating_hotel')->unsigned();
            $table->decimal('harga_hotel', 15, 2);
            $table->text('catatan_hotel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
