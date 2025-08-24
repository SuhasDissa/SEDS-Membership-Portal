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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('student_id')->unique();
            $table->enum('year', ['1', '2', '3', '4', 'graduate']);
            $table->string('faculty');
            $table->string('department');
            $table->text('motivation')->nullable();
            $table->json('interests')->nullable();
            $table->boolean('has_programming_experience')->default(false);
            $table->text('programming_languages')->nullable();
            $table->boolean('has_space_projects_experience')->default(false);
            $table->text('space_projects_description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
