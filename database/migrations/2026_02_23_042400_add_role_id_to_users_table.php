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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')->constrained('roles')->nullOnDelete();
        });

        // Migrate existing roles
        $existingRoles = \Illuminate\Support\Facades\DB::table('users')
            ->select('role')
            ->whereNotNull('role')
            ->distinct()
            ->pluck('role');

        foreach ($existingRoles as $roleName) {
            $roleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
                'name' => $roleName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Illuminate\Support\Facades\DB::table('users')
                ->where('role', $roleName)
                ->update(['role_id' => $roleId]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->string('role')->nullable()->after('email');
        });
    }
};
