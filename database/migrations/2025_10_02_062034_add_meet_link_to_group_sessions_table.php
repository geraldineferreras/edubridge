<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('group_sessions', function (Blueprint $table) {
        $table->string('meet_link')->nullable();
    });
}

public function down()
{
    Schema::table('group_sessions', function (Blueprint $table) {
        $table->dropColumn('meet_link');
    });
}


};
