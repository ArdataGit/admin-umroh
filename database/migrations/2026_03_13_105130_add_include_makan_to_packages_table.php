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
            $table->boolean('is_include_makan_1')->default(false)->after('harga_hpp_1');
            $table->boolean('is_include_makan_2')->default(false)->after('harga_hpp_2');
        });

        Schema::table('paket_hajis', function (Blueprint $table) {
            $table->boolean('is_include_makan_1')->default(false)->after('harga_hpp_1');
            $table->boolean('is_include_makan_2')->default(false)->after('harga_hpp_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_umrohs', function (Blueprint $table) {
            $table->dropColumn(['is_include_makan_1', 'is_include_makan_2']);
        });

        Schema::table('paket_hajis', function (Blueprint $table) {
            $table->dropColumn(['is_include_makan_1', 'is_include_makan_2']);
        });
    }
};
