<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('session_timetable', function (Blueprint $table) {
            $table->dropColumn(['session_time', 'capacity']);
        });
    }

    public function down()
    {
        Schema::table('session_timetable', function (Blueprint $table) {
            $table->string('session_time')->nullable();
            $table->integer('capacity')->nullable();
        });
    }
};