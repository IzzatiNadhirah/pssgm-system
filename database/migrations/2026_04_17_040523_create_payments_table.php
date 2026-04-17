<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id('payment_ID');
            $table->string('payment_code')->unique()->nullable(); // Storing PAY0001
            $table->decimal('amount', 8, 2);
            $table->date('payment_date');
            $table->string('payment_status');
            $table->string('receipt_path')->nullable();
            
            $table->unsignedBigInteger('member_ID');
            $table->foreign('member_ID')->references('member_ID')->on('membership')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::unprepared("
            CREATE SEQUENCE payment_seq START 1;

            CREATE OR REPLACE FUNCTION generate_payment_code()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.payment_code := 'PAY' || LPAD(nextval('payment_seq')::text, 4, '0');
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER set_payment_code
            BEFORE INSERT ON payment
            FOR EACH ROW
            EXECUTE FUNCTION generate_payment_code();
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS set_payment_code ON payment;
            DROP FUNCTION IF EXISTS generate_payment_code();
            DROP SEQUENCE IF EXISTS payment_seq;
        ");
        Schema::dropIfExists('payment');
    }
};