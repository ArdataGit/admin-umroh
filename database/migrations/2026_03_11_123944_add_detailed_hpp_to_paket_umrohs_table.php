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
            $table->decimal('hpp_quad1', 15, 2)->nullable()->after('harga_hpp_1');
            $table->decimal('hpp_triple1', 15, 2)->nullable()->after('hpp_quad1');
            $table->decimal('hpp_double1', 15, 2)->nullable()->after('hpp_triple1');
            
            $table->decimal('hpp_quad2', 15, 2)->nullable()->after('harga_hpp_2');
            $table->decimal('hpp_triple2', 15, 2)->nullable()->after('hpp_quad2');
            $table->decimal('hpp_double2', 15, 2)->nullable()->after('hpp_triple2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_umrohs', function (Blueprint $table) {
            $table->dropColumn(['hpp_quad1', 'hpp_triple1', 'hpp_double1', 'hpp_quad2', 'hpp_triple2', 'hpp_double2']);
        });
    }
};
