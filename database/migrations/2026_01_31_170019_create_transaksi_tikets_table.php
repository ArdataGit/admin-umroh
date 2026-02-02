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
        Schema::create('transaksi_tikets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // TI-XXX (Ticket Invoice)
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            $table->date('tanggal_transaksi');
            
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            
            $table->decimal('total_transaksi', 15, 2)->default(0);
            
            $table->enum('status_transaksi', ['process', 'completed', 'cancelled'])->default('process');
            
            $table->text('alamat_transaksi')->nullable();
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_tikets');
    }
};
