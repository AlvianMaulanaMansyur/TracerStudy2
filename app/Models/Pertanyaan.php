<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';
    protected $fillable = [
        'data_pertanyaan',
        'kuesioner_id',
    ];

    public function kuesioner(){
        return $this->belongsTo(Kuesioner::class);
    }
}
