<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban_kuesioner extends Model
{
    use HasFactory;

    protected $table = 'jawaban_kuesioner';

    protected $fillable = [
        'jawaban',
        'alumni_nim',
        'pertanyaan_id',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class);
    }
}
