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
            $table->string('university_id')->unique()->nullable()->after('email');
            $table->enum('faculty', [
                'Engineering',
                'IT',
                'Architecture',
                'Business',
                'Science',
                'Other'
            ])->nullable()->after('university_id');
            $table->string('department')->nullable()->after('faculty');
            $table->string('phone')->nullable()->after('department');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->boolean('is_admin')->default(false)->after('avatar_url');
            $table->boolean('is_approved')->default(false)->after('is_admin');
            $table->string('google_id')->nullable()->unique()->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'university_id',
                'faculty',
                'department',
                'phone',
                'avatar_url',
                'is_admin',
                'is_approved',
                'google_id'
            ]);
        });
    }
};
