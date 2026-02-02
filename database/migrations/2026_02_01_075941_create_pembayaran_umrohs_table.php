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
        Schema::create('pembayaran_umrohs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_umroh_id')->constrained('customer_umrohs')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->string('metode_pembayaran'); // Cash, Transfer, etc.
            $table->string('status_pembayaran')->default('paid');
            $table->string('kode_referensi')->nullable();
            $table->date('tanggal_pembayaran');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_umrohs');
    }
};
