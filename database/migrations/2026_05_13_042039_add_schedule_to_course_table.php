<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->string('session_time')->nullable();
            $table->integer('capacity')->nullable();
        });
    }

    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->dropColumn(['session_time', 'capacity']);
        });
    }
};