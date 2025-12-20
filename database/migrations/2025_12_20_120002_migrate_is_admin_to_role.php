<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing is_admin values to role
        // is_admin = true -> role = 300 (Admin)
        DB::table('users')
            ->where('is_admin', true)
            ->update(['role' => 300]);

        // is_admin = false -> role = 100 (Member) - already default
        DB::table('users')
            ->where('is_admin', false)
            ->update(['role' => 100]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert role values back to is_admin
        DB::table('users')
            ->where('role', 300)
            ->update(['is_admin' => true]);

        DB::table('users')
            ->whereIn('role', [100, 200])
            ->update(['is_admin' => false]);
    }
};
