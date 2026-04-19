<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('staff', function (Blueprint $table) {
        // Add the role column, default it to a regular admin
        $table->string('role')->default('admin');
    });
}

public function down(): void
{
    Schema::table('staff', function (Blueprint $table) {
        // Remove it if we ever rollback
        $table->dropColumn('role');
    });
}
};
