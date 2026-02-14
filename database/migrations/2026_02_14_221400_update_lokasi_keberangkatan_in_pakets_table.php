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
        Schema::table('paket_hajis', function (Blueprint $table) {
            $table->string('lokasi_keberangkatan')->change();
        });

        Schema::table('paket_umrohs', function (Blueprint $table) {
            $table->string('lokasi_keberangkatan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_hajis', function (Blueprint $table) {
            $table->enum('lokasi_keberangkatan', ['Jakarta', 'Surabaya', 'Makasar', 'Balikpapan', 'Medan', 'Pekanbaru', 'Denpasar', 'Lombok', 'Jambi', 'Batam'])->change();
        });

        Schema::table('paket_umrohs', function (Blueprint $table) {
            $table->enum('lokasi_keberangkatan', ['Jakarta', 'Surabaya', 'Makasar', 'Balikpapan', 'Medan', 'Pekanbaru', 'Denpasar', 'Lombok', 'Jambi', 'Batam'])->change();
        });
    }
};
