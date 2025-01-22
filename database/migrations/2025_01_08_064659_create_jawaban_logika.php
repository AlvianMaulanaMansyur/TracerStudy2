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
        Schema::create('jawaban_logika', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni');
            $table->string('logika_id'); // ID logika
            $table->text('jawaban'); // Jawaban untuk logika
            $table->timestamps();

            // Menambahkan foreign key untuk logika_id
            $table->foreign('logika_id')->references('id')->on('logika'); // Relasi ke tabel logika
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_logika');
    }
};
