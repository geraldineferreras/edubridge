<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            // mentee is a user (users table)
            $table->foreignId('mentee_user_id')->constrained('users')->cascadeOnDelete();
            // mentor is from mentors table (you already have this)
            $table->foreignId('mentor_id')->constrained('mentors')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->unique(['mentee_user_id', 'mentor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
