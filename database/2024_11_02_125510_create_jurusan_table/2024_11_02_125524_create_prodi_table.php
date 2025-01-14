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
        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi', 100);
            $table->foreignId('jurusan_id')->constrained('jurusan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    //  public function down()
    // {
    //     Schema::table('admin', function (Blueprint $table) {
    //         $table->dropForeign(['prodi_id']); // Hapus foreign key di sini
    //     });

    //     Schema::dropIfExists('admin');
    // }
    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
