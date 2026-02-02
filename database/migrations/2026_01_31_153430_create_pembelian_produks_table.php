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
        Schema::create('pembelian_produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->date('tanggal_pembelian');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_pembayaran', 15, 2);
            $table->enum('status_pembayaran', ['order', 'delivery', 'completed'])->default('order');
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris', 'other']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_produks');
    }
};
