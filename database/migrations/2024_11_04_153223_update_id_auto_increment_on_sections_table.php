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
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->dropColumn('id');
        });

        Schema::table('sections', function (Blueprint $table) {
            // Tambahkan kembali kolom 'id' sebagai auto-increment
            $table->bigIncrements('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->bigInteger('id')->first();
        });
    }
};
