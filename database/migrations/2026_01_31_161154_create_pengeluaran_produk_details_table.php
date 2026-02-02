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
        Schema::create('pengeluaran_produk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_produk_id')->constrained('pengeluaran_produks')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            
            $table->integer('quantity');
            $table->decimal('harga_satuan', 15, 2); // Taking the Harga Jual at the moment of transaction
            $table->decimal('total_harga', 15, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_produk_details');
    }
};
