<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban_logika extends Model
{
    use HasFactory;

    protected $table = 'jawaban_logika';

    protected $fillable = [
        'jawaban',
        'logika_id',
    ];

    public function logika()
    {
        return $this->belongsTo(Logika::class);
    }
}
