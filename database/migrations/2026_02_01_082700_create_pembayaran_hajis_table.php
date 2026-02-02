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
        Schema::create('pembayaran_hajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_haji_id')->constrained('customer_hajis')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->string('metode_pembayaran'); // cash, transfer, etc.
            $table->string('status_pembayaran')->default('checked'); // paid, pending, checked
            $table->string('kode_referensi')->nullable();
            $table->dateTime('tanggal_pembayaran'); // payment date
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hajis');
    }
};
