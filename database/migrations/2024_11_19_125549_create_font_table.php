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
        Schema::create('font', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('jenis_font'); // Kolom untuk jenis font
            $table->string('warna_font'); // Kolom untuk warna font
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('font');
    }
};
