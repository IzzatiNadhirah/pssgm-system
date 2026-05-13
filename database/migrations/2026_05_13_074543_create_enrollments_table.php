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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enroll_ID');
            $table->unsignedBigInteger('user_ID');
            $table->unsignedBigInteger('course_ID');
            $table->timestamp('enroll_date')->useCurrent();
            $table->timestamps();

            // Foreign keys (Kalau awak ada error masa migrate, buang je dua baris foreign key ni)
            $table->foreign('user_ID')->references('user_ID')->on('users')->onDelete('cascade');
            $table->foreign('course_ID')->references('course_ID')->on('course')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
