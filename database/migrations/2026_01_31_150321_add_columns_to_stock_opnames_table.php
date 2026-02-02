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
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->integer('stok_awal')->after('tipe_adjustment');
            $table->integer('stok_akhir')->after('koreksi_stock');
            // Mock User ID for now since auth might not be fully set up or we want simple string
            $table->string('user_id')->nullable()->after('stok_akhir'); 
            $table->string('status_approval')->default('Approved')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            //
        });
    }
};
