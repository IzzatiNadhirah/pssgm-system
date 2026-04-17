<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor', function (Blueprint $table) {
            $table->id('instructor_ID');
            $table->string('instructor_code')->unique()->nullable(); 
            $table->string('name');
            $table->text('address');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('tel_number');
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE instructor_seq START 1;

            CREATE OR REPLACE FUNCTION generate_instructor_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.instructor_code := 'INS' || LPAD(nextval('instructor_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_instructor_code
            BEFORE INSERT ON instructor
            FOR EACH ROW
            EXECUTE FUNCTION generate_instructor_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_instructor_code ON instructor;
            DROP FUNCTION IF EXISTS generate_instructor_code();
            DROP SEQUENCE IF EXISTS instructor_seq;
        ");
        Schema::dropIfExists('instructor');
    }
};