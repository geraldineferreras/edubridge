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
    Schema::create('mentors', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->integer('age')->nullable();
        $table->string('gender')->nullable();
        $table->string('password');
        $table->string('job_title')->nullable();
        $table->string('company')->nullable();
        $table->string('location')->nullable();
        $table->string('category')->nullable();
        $table->string('skills')->nullable();
        $table->text('bio')->nullable();
        $table->string('website')->nullable();
        $table->string('twitter')->nullable();
        $table->string('years_experience')->nullable();
        $table->string('relevant_skills')->nullable();
        $table->string('industries')->nullable();
        $table->text('mentoring_experience')->nullable();
        $table->string('notable_projects')->nullable();
        $table->string('profile_photo')->nullable();
        $table->string('resume')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentors');
    }
};
