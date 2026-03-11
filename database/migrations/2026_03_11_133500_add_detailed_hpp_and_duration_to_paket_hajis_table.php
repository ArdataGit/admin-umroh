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
        Schema::table('paket_hajis', function (Blueprint $table) {
            // HPP Details Variant 1
            $table->decimal('hpp_quad1', 15, 2)->nullable()->after('harga_hpp_1');
            $table->decimal('hpp_triple1', 15, 2)->nullable()->after('hpp_quad1');
            $table->decimal('hpp_double1', 15, 2)->nullable()->after('hpp_triple1');
            
            // HPP Details Variant 2
            $table->decimal('hpp_quad2', 15, 2)->nullable()->after('harga_hpp_2');
            $table->decimal('hpp_triple2', 15, 2)->nullable()->after('hpp_quad2');
            $table->decimal('hpp_double2', 15, 2)->nullable()->after('hpp_triple2');

            // Hotel Durations Variant 1
            $table->integer('hari_mekkah_1')->default(0)->after('hotel_mekkah_1');
            $table->integer('hari_madinah_1')->default(0)->after('hotel_madinah_1');
            $table->integer('hari_transit_1')->default(0)->after('hotel_transit_1');

            // Hotel Durations Variant 2
            $table->integer('hari_mekkah_2')->default(0)->after('hotel_mekkah_2');
            $table->integer('hari_madinah_2')->default(0)->after('hotel_madinah_2');
            $table->integer('hari_transit_2')->default(0)->after('hotel_transit_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_hajis', function (Blueprint $table) {
            $table->dropColumn([
                'hpp_quad1', 'hpp_triple1', 'hpp_double1', 
                'hpp_quad2', 'hpp_triple2', 'hpp_double2',
                'hari_mekkah_1', 'hari_madinah_1', 'hari_transit_1',
                'hari_mekkah_2', 'hari_madinah_2', 'hari_transit_2'
            ]);
        });
    }
};
