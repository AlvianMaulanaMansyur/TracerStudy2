<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $fillable = [
        'nama_prodi',
        'jurusan_id',
    ];

    public function alumni(){
        return $this->belongsTo(Alumni::class);
    }

    public function admin() {
        return $this->hasMany(Admin::class);
    }
}
