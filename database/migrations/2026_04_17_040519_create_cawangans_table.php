<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cawangan', function (Blueprint $table) {
            $table->id('caw_ID');
            $table->string('caw_code')->unique()->nullable(); 
            $table->string('caw_name');
            $table->text('caw_address');
            
            $table->unsignedBigInteger('staff_ID');
            $table->foreign('staff_ID')->references('staff_ID')->on('staff')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE cawangan_seq START 1;

            CREATE OR REPLACE FUNCTION generate_cawangan_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.caw_code := 'CAW' || LPAD(nextval('cawangan_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_cawangan_code
            BEFORE INSERT ON cawangan
            FOR EACH ROW
            EXECUTE FUNCTION generate_cawangan_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_cawangan_code ON cawangan;
            DROP FUNCTION IF EXISTS generate_cawangan_code();
            DROP SEQUENCE IF EXISTS cawangan_seq;
        ");
        Schema::dropIfExists('cawangan');
    }
};