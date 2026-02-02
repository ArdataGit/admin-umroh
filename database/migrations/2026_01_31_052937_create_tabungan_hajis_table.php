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
        Schema::create('tabungan_hajis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tabungan')->unique();
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade');
            $table->date('tanggal_pendaftaran');
            $table->enum('bank_tabungan', ['Bank Travel', 'Bank BSI', 'Bank Muamalat', 'Bank BRI', 'Bank BNI', 'Bank BCA', 'Bank Mandiri']);
            $table->string('rekening_tabungan');
            $table->enum('status_tabungan', ['active', 'non-active']);
            $table->decimal('setoran_tabungan', 15, 2);
            $table->enum('metode_pembayaran', ['Cash', 'Transfer', 'Debit', 'QRIS', 'Other']);
            $table->text('catatan_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabungan_hajis');
    }
};
