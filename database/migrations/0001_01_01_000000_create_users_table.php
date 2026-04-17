<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_ID'); 
            $table->string('user_code')->unique()->nullable(); // Will store USR0001
            $table->string('name');
            $table->string('icNo')->unique();
            $table->string('password');
            $table->string('tel_number');
            $table->string('bengkung_level');
            $table->string('email')->unique();
            $table->text('address');
            $table->timestamps();
        });

        // 2. Default Laravel Password Reset Table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Default Laravel Sessions Table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 4. PostgreSQL Sequence & Trigger for user_code
        DB::unprepared("
            CREATE SEQUENCE users_seq START 1;

            CREATE OR REPLACE FUNCTION generate_user_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.user_code := 'USR' || LPAD(nextval('users_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_user_code
            BEFORE INSERT ON users
            FOR EACH ROW
            EXECUTE FUNCTION generate_user_code();
        ");
    }

    public function down(): void
    {
        // Drop the trigger and sequence first
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_user_code ON users;
            DROP FUNCTION IF EXISTS generate_user_code();
            DROP SEQUENCE IF EXISTS users_seq;
        ");

        // Then drop the tables
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};