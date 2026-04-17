<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->id('member_ID');
            $table->string('member_code')->unique()->nullable(); // Storing MEM0001
            $table->string('member_type');
            
            $table->unsignedBigInteger('user_ID');
            $table->foreign('user_ID')->references('user_ID')->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE membership_seq START 1;

            CREATE OR REPLACE FUNCTION generate_membership_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.member_code := 'MEM' || LPAD(nextval('membership_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_membership_code
            BEFORE INSERT ON membership
            FOR EACH ROW
            EXECUTE FUNCTION generate_membership_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_membership_code ON membership;
            DROP FUNCTION IF EXISTS generate_membership_code();
            DROP SEQUENCE IF EXISTS membership_seq;
        ");
        Schema::dropIfExists('membership');
    }
};