<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kuesioner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kuesioner';
    protected $fillable = [
        'judul_kuesioner',
        'admin_id'
    ];

    public function pertanyaan() {
        return $this->hasMany(Pertanyaan::class);
    }
}
