<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelanggang', function (Blueprint $table) {
            $table->id('gel_ID');
            $table->string('gel_code')->unique()->nullable(); 
            $table->string('gel_name');
            $table->text('gel_address');
            
            $table->unsignedBigInteger('caw_ID');
            $table->foreign('caw_ID')->references('caw_ID')->on('cawangan')->onDelete('cascade');

            $table->unsignedBigInteger('instructor_ID');
            $table->foreign('instructor_ID')->references('instructor_ID')->on('instructor')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE gelanggang_seq START 1;

            CREATE OR REPLACE FUNCTION generate_gelanggang_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.gel_code := 'GEL' || LPAD(nextval('gelanggang_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_gelanggang_code
            BEFORE INSERT ON gelanggang
            FOR EACH ROW
            EXECUTE FUNCTION generate_gelanggang_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_gelanggang_code ON gelanggang;
            DROP FUNCTION IF EXISTS generate_gelanggang_code();
            DROP SEQUENCE IF EXISTS gelanggang_seq;
        ");
        Schema::dropIfExists('gelanggang');
    }
};