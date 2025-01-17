<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opsi extends Model
{
    use HasFactory;

    protected $table = 'opsi';
    public $incrementing = false; // Kolom id bukan auto-increment
    protected $keyType = 'string'; // Jika tipe datanya string
    protected $fillable = [
        'id',
        'pertanyaan_id',
        'teks_opsi',
    ];


    public function logika()
    {
    return $this->hasOne(Logika::class, 'opsi_id', 'id');
    }
}
