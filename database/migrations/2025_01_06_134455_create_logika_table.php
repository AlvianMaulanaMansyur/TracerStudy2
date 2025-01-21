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
    $table->string('pertanyaan_id');
    $table->json('data_pertanyaan');
    $table->foreign('pertanyaan_id')
        ->references('id')
        ->on('pertanyaan')
        ->onDelete('cascade'); // Tambahkan ini

        // $table->string('kuesioner_id'); 
        // $table->foreign('kuesioner_id')
        //     ->references('id')
        //     ->on('kuesioner'); // Tambahkan ini
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
