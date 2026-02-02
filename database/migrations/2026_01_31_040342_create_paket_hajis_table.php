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
        Schema::create('paket_hajis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_paket')->unique();
            $table->string('nama_paket');
            $table->date('tanggal_keberangkatan');
            $table->integer('jumlah_hari');
            $table->enum('status_paket', ['active', 'completed']);
            $table->integer('kuota_jamaah');
            
            // Flight Info
            $table->foreignId('maskapai_id')->constrained('maskapais')->onDelete('cascade');
            $table->enum('rute_penerbangan', ['direct', 'transit']);
            $table->enum('lokasi_keberangkatan', ['Jakarta', 'Surabaya', 'Makasar', 'Balikpapan', 'Medan', 'Pekanbaru', 'Denpasar', 'Lombok', 'Jambi', 'Batam']);

            // Variant 1
            $table->string('jenis_paket_1');
            $table->foreignId('hotel_mekkah_1')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('hotel_madinah_1')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('hotel_transit_1')->nullable()->constrained('hotels')->onDelete('cascade');
            $table->decimal('harga_hpp_1', 15, 2);
            $table->decimal('harga_quad_1', 15, 2);
            $table->decimal('harga_triple_1', 15, 2);
            $table->decimal('harga_double_1', 15, 2);

            // Variant 2
            $table->string('jenis_paket_2')->nullable();
            $table->foreignId('hotel_mekkah_2')->nullable()->constrained('hotels')->onDelete('cascade');
            $table->foreignId('hotel_madinah_2')->nullable()->constrained('hotels')->onDelete('cascade');
            $table->foreignId('hotel_transit_2')->nullable()->constrained('hotels')->onDelete('cascade');
            $table->decimal('harga_hpp_2', 15, 2)->nullable();
            $table->decimal('harga_quad_2', 15, 2)->nullable();
            $table->decimal('harga_triple_2', 15, 2)->nullable();
            $table->decimal('harga_double_2', 15, 2)->nullable();

            // Details & Media
            $table->text('termasuk_paket')->nullable();
            $table->text('tidak_termasuk_paket')->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->text('catatan_paket')->nullable();
            $table->string('foto_brosur')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_hajis');
    }
};
