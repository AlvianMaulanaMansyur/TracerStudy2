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
        Schema::create('halaman', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('judul_halaman'); // Kolom untuk menyimpan judul halaman
            $table->text('deskripsi_halaman')->nullable(); // Kolom untuk menyimpan deskripsi halaman, bisa null
            $table->timestamps(); // Kolom untuk menyimpan waktu pembuatan dan pembaruan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halaman');
    }
};
