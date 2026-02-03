<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records from 'Lunas' to 'Checked'
        DB::table('bonus_payouts')->where('status_pembayaran', 'Lunas')->update(['status_pembayaran' => 'Checked']);
        
        // Change the default value from 'Lunas' to 'Checked'
        Schema::table('bonus_payouts', function (Blueprint $table) {
            $table->string('status_pembayaran')->default('Checked')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert records from 'Checked' to 'Lunas'
        DB::table('bonus_payouts')->where('status_pembayaran', 'Checked')->update(['status_pembayaran' => 'Lunas']);
        
        Schema::table('bonus_payouts', function (Blueprint $table) {
            $table->string('status_pembayaran')->default('Lunas')->change();
        });
    }
};
