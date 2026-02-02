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
        Schema::create('pengeluaran_produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengeluaran')->unique(); // PK-XXX
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->date('tanggal_pengeluaran');
            
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_nominal', 15, 2)->default(0); // Total Value of goods + shipping - discount + tax
            
            $table->enum('status_pengeluaran', ['process', 'delivery', 'completed'])->default('process');
            $table->enum('metode_pengiriman', ['kurir', 'kantor', 'delivery', 'order'])->default('kantor');
            
            $table->text('alamat_pengiriman')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_produks');
    }
};
