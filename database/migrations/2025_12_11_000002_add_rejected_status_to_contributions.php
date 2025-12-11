<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add rejection reason column
        Schema::table('contributions', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
            $table->string('status_new')->default('pending')->after('status');
        });

        // Copy data
        \DB::table('contributions')->update(['status_new' => \DB::raw('status')]);

        // Drop old column and rename new one
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('contributions', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
