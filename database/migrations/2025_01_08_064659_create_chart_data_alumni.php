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
        Schema::create('chart_data_alumni', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap entri
            $table->json('data_chart_alumni'); // Kolom untuk menyimpan data chart dalam format JSON
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_data_alumni');
    }
};
