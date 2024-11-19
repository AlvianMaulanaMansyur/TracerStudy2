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
            $table->id();
            // $table->text('teks_pertanyaan');
            // $table->string('tipe_pertanyaan', 50);
            // $table->longText('opsi_jawaban')->nullable();
            $table->json('data_pertanyaan');
            $table->foreignId('kuesioner_id')->constrained('kuesioner');
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
