<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart_data extends Model
{
    use HasFactory;

    protected $table = 'chart_data';
    protected $fillable = ['data_chart', 'kuesioner_id'];

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'halaman_id');
    }
}
