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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alumni', 150);
            $table->char('npwp', 16);
            $table->char('nik', 16);
            $table->string('email', 50);
            $table->longText('password');
            $table->string('jenis_kelamin', 10);
            $table->integer('angkatan');
            $table->integer('tahun_lulus');
            $table->tinyInteger('gelombang_wisuda');
            $table->string('alamat', 150);
            $table->string('no_telepon', 25);
            $table->string('foto_profil', 255);
            $table->tinyInteger('status_verifikasi');
            $table->foreignId('prodi_id')->constrained('prodi');
            $table->foreignId('kota_id')->constrained('kota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
