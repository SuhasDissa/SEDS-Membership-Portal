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
        // Remove the old enum check constraint
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_faculty_check');
        
        // Change the faculty column to string type (removing enum)
        Schema::table('users', function (Blueprint $table) {
            $table->string('faculty')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally restore the enum constraint
        Schema::table('users', function (Blueprint $table) {
            $table->enum('faculty', [
                'Engineering',
                'IT',
                'Architecture',
                'Business',
                'Science',
                'Other'
            ])->nullable()->change();
        });
    }
};
