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
        Schema::create('product_selected', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->integer('user_id')->nullable(); // to allow user to Add to Cart WITHOUT LOGIN, then later when user logins, we'll use their `user_id`
            $table->integer('product_id');
            $table->string('size');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_selected');
    }
};
