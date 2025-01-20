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
        Schema::create('pertanyaan', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->json('data_pertanyaan');
    $table->string('kuesioner_id'); 
    $table->string('halaman_id'); 
    $table->foreign('kuesioner_id')
        ->references('id')
        ->on('kuesioner'); // Tambahkan ini
    $table->foreign('halaman_id')
        ->references('id')
        ->on('halaman')
        ->onDelete('cascade'); // Tambahkan ini
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};
