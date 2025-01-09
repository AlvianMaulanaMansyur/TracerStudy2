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
            $table->string('kuesioner_id'); // Ubah ini menjadi string
            $table->string('halaman_id'); // Ubah ini menjadi string
            $table->foreign('kuesioner_id')->references('id')->on('kuesioner'); // Definisikan foreign key secara manual
            $table->foreign('halaman_id')->references('id')->on('halaman'); // Definisikan foreign key secara manual
        
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
