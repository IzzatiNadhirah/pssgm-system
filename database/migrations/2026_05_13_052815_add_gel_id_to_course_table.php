<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course', function (Blueprint $table) {
            // Tambah column gel_ID
            $table->unsignedBigInteger('gel_ID')->nullable();
            
            // Buat relationship dengan table gelanggang
            $table->foreign('gel_ID')->references('gel_ID')->on('gelanggang')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('course', function (Blueprint $table) {
            $table->dropForeign(['gel_ID']);
            $table->dropColumn('gel_ID');
        });
    }
};