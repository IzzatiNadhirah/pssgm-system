<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // KITA GUNA NAMA 'instructor' (TANPA 'S')
        Schema::table('instructor', function (Blueprint $table) {
            $table->unsignedBigInteger('caw_ID')->nullable()->after('tel_number'); 
        });
    }

    public function down()
    {
        Schema::table('instructor', function (Blueprint $table) {
            $table->dropColumn('caw_ID');
        });
    }
};