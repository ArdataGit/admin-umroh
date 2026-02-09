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
        Schema::table('hotels', function (Blueprint $table) {
            $table->enum('kurs', ['USD', 'SAR', 'MYR', 'IDR'])->default('IDR')->after('catatan_hotel');
            $table->decimal('kurs_asing', 15, 2)->default(0)->after('kurs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['kurs', 'kurs_asing']);
        });
    }
};
