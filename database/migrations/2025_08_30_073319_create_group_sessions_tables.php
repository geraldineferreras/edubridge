<?php

// database/migrations/2025_08_22_000001_create_group_sessions_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('group_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('mentors')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->integer('capacity')->default(10); // max mentees allowed
            $table->enum('status', ['open', 'full', 'ended'])->default('open');
            $table->timestamps();
        });

        Schema::create('group_session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('group_sessions')->cascadeOnDelete();
            $table->foreignId('mentee_id')->constrained('mentees')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['session_id', 'mentee_id']); // prevent duplicate joins
        });
    }

    public function down(): void {
        Schema::dropIfExists('group_session_participants');
        Schema::dropIfExists('group_sessions');
    }
};
