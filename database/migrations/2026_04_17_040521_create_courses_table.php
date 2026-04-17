<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course', function (Blueprint $table) {
            $table->id('course_ID');
            $table->string('course_code')->unique()->nullable(); // Storing CRS0001
            $table->string('course_type');
            
            $table->unsignedBigInteger('instructor_ID');
            $table->foreign('instructor_ID')->references('instructor_ID')->on('instructor')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE course_seq START 1;

            CREATE OR REPLACE FUNCTION generate_course_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.course_code := 'CRS' || LPAD(nextval('course_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_course_code
            BEFORE INSERT ON course
            FOR EACH ROW
            EXECUTE FUNCTION generate_course_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_course_code ON course;
            DROP FUNCTION IF EXISTS generate_course_code();
            DROP SEQUENCE IF EXISTS course_seq;
        ");
        Schema::dropIfExists('course');
    }
};