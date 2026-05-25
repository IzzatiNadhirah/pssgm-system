<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_timetable', function (Blueprint $table) {
            // Kita letak ID unik yang standard untuk setiap sesi
            $table->id(); 
            
            $table->unsignedBigInteger('course_ID');
            $table->unsignedBigInteger('gel_ID'); // Tambah kolum lokasi
            
            $table->string('session_time'); 
            $table->integer('capacity');

            // Set Foreign Keys
            $table->foreign('course_ID')->references('course_ID')->on('course')->onDelete('cascade');
            $table->foreign('gel_ID')->references('gel_ID')->on('gelanggang')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_timetable');
    }
};