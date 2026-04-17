<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_timetable', function (Blueprint $table) {
            // Define the columns first
            $table->unsignedBigInteger('course_ID');
            $table->unsignedBigInteger('user_ID');
            
            $table->string('session_time'); 
            $table->integer('capacity');

            // Set them as Foreign Keys
            $table->foreign('course_ID')->references('course_ID')->on('course')->onDelete('cascade');
            $table->foreign('user_ID')->references('user_ID')->on('users')->onDelete('cascade');

            // Tell Laravel these two columns combined are the Primary Key
            $table->primary(['course_ID', 'user_ID']);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_timetable');
    }
};