<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halaman extends Model
{
    //
    use HasFactory;

    protected $table = 'halaman';
    public $incrementing = false; // Kolom id bukan auto-increment
    protected $keyType = 'string'; // Jika tipe datanya string
    protected $fillable = ['id', 'judul_halaman', 'deskripsi_halaman'];

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'halaman_id');
    }
}
