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
    Schema::create('feedbacks', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('mentor_id');
        $table->string('author');
        $table->text('comment');
        $table->tinyInteger('rating')->nullable();
        $table->timestamps();

        $table->foreign('mentor_id')->references('id')->on('mentors')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
