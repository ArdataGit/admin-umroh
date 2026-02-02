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
        Schema::create('customer_hajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keberangkatan_haji_id')->constrained('keberangkatan_hajis')->onDelete('cascade');
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->enum('tipe_kamar', ['quad', 'triple', 'double']);
            $table->integer('jumlah_jamaah');
            $table->string('nama_keluarga')->nullable();
            
            // Financials
            $table->decimal('harga_paket', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('sisa_tagihan', 15, 2);
            $table->string('metode_pembayaran'); // cash, transfer, etc

            // Status Checklists
            $table->boolean('status_visa')->default(false);
            $table->boolean('status_tiket')->default(false);
            $table->boolean('status_siskopatuh')->default(false);
            $table->boolean('status_perlengkapan')->default(false);

            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_hajis');
    }
};
