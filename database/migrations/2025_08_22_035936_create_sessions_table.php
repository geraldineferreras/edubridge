<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('mentee_sessions')) {
        Schema::create('mentee_sessions', function (Blueprint $table) {
            $table->id();

            // Foreign keys instead of names
            $table->unsignedBigInteger('mentee_id');
            $table->unsignedBigInteger('mentor_id');

            $table->string('subject');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('session_date');
            $table->timestamps();

            // Set foreign key constraints
            $table->foreign('mentee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mentor_id')->references('id')->on('mentors')->onDelete('cascade');
        });
    }
}


    public function down(): void
    {
        Schema::dropIfExists('mentee_sessions'); // drop correct table
    }
};
