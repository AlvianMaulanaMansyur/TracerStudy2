<?php

use App\Models\Pertanyaan;
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
        Schema::create('jawaban_kuesioner', function (Blueprint $table) {
            $table->id();
            $table->string('jawaban', 255);
            $table->foreignId('alumni_id')->constrained('alumni');
            $table->string(column: 'pertanyaan_id'); // Ubah ini menjadi string
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan')->onDelete('cascade'); // Definisikan foreign key secara manual
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_kuesioner');
    }
};
