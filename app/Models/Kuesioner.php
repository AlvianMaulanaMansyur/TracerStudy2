<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Kuesioner extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false; // Kolom id bukan auto-increment
    protected $keyType = 'string'; // Jika tipe datanya string
    protected $table = 'kuesioner';
    protected $fillable = [
        'id',
        'judul_kuesioner',
        'slug',
        'admin_id'
    ];

    public function halaman()
    {
        return $this->hasMany(Halaman::class, 'kuesioner_id'); // Sesuaikan dengan relasi yang benar
    }

    public function logika()
    {
        return $this->hasMany(Logika::class, 'kuesioner_id'); // Sesuaikan dengan relasi yang benar
    }

    public function pertanyaan() {
        return $this->hasMany(Pertanyaan::class);
    }

    
}
