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
        Schema::table('products', function (Blueprint $table) {
            // Hapus kolom 'id' lama jika sudah ada
            $table->dropColumn('id');
        });

        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kembali kolom 'id' sebagai auto-increment
            $table->bigIncrements('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengembalikan kolom 'id' menjadi tipe bigInteger tanpa auto-increment jika rollback
            $table->bigInteger('id')->first();
        });
    }
};
