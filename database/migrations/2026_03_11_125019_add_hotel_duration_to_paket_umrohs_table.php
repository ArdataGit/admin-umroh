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
        Schema::table('paket_umrohs', function (Blueprint $table) {
            $table->integer('hari_mekkah_1')->nullable()->after('hotel_mekkah_1');
            $table->integer('hari_madinah_1')->nullable()->after('hotel_madinah_1');
            $table->integer('hari_transit_1')->nullable()->after('hotel_transit_1');
            
            $table->integer('hari_mekkah_2')->nullable()->after('hotel_mekkah_2');
            $table->integer('hari_madinah_2')->nullable()->after('hotel_madinah_2');
            $table->integer('hari_transit_2')->nullable()->after('hotel_transit_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_umrohs', function (Blueprint $table) {
            $table->dropColumn([
                'hari_mekkah_1', 'hari_madinah_1', 'hari_transit_1',
                'hari_mekkah_2', 'hari_madinah_2', 'hari_transit_2'
            ]);
        });
    }
};
