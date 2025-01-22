<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logika extends Model
{
    use HasFactory;

    protected $table = 'logika';
    public $incrementing = false; // Kolom id bukan auto-increment
    protected $keyType = 'string'; // Jika tipe datanya string
    protected $fillable = [
        'id',
        'pertanyaan_id',
        'data_pertanyaan',
        'kuesioner_id',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}
