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
        Schema::table('maskapais', function (Blueprint $table) {
            $table->string('kurs')->default('IDR')->after('nama_maskapai');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('maskapais', 'kurs')) {
            Schema::table('maskapais', function (Blueprint $table) {
                $table->dropColumn('kurs');
            });
        }
    }
};
