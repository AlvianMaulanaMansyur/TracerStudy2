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
        Schema::create('logika', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('data_pertanyaan');     
            $table->string(column: 'pertanyaan_id'); // Ubah ini menjadi string
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan'); // Definisikan foreign key secara manual   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logika');
    }
};
