<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'data_pertanyaan',
        'halaman_id',
        'kuesioner_id',
    ];

    public function halaman()
    {
        return $this->belongsTo(Halaman::class, 'halaman_id');
    }

    public function logika()
    {
        return $this->hasMany(Logika::class, 'pertanyaan_id');
    }

}