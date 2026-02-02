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
        Schema::create('customer_umrohs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keberangkatan_umroh_id')->constrained('keberangkatan_umrohs')->onDelete('cascade');
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null');
            
            // Transaction Details
            $table->enum('tipe_kamar', ['quad', 'triple', 'double']);
            $table->integer('jumlah_jamaah')->default(1);
            $table->string('nama_keluarga')->nullable();
            
            // Financials
            $table->decimal('harga_paket', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2); // (harga * jumlah) - diskon
            $table->decimal('total_bayar', 15, 2)->default(0); // DP
            $table->decimal('sisa_tagihan', 15, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'debit', 'qris', 'other']);
            
            // Statuses
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
        Schema::dropIfExists('customer_umrohs');
    }
};
