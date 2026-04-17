<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_ID'); 
            $table->string('staff_code')->unique()->nullable(); 
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE staff_seq START 1;

            CREATE OR REPLACE FUNCTION generate_staff_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.staff_code := 'ST' || LPAD(nextval('staff_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_staff_code
            BEFORE INSERT ON staff
            FOR EACH ROW
            EXECUTE FUNCTION generate_staff_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_staff_code ON staff;
            DROP FUNCTION IF EXISTS generate_staff_code();
            DROP SEQUENCE IF EXISTS staff_seq;
        ");
        Schema::dropIfExists('staff');
    }
};