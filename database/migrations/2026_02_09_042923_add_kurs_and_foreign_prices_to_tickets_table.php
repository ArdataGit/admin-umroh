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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('kurs')->default('IDR')->after('harga_jual');
            $table->decimal('harga_modal_asing', 15, 2)->default(0)->after('kurs');
            $table->decimal('harga_jual_asing', 15, 2)->default(0)->after('harga_modal_asing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['kurs', 'harga_modal_asing', 'harga_jual_asing']);
        });
    }
};
