<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
    Schema::table('group_sessions', function (Blueprint $table) {
        $table->string('room_id')->nullable()->unique()->after('status');
    });
}


    public function down(): void {
        Schema::table('group_sessions', function (Blueprint $table) {
            $table->dropColumn('room_id');
        });
    }
};

