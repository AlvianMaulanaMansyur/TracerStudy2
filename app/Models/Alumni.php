<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Alumni extends Authenticatable
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable =
    [
        'nim',
        'nama_alumni',
        'npwp',
        'nik',
        'email',
        'password',
        'jenis_kelamin',
        'angkatan',
        'tahun_lulus',
        'gelombang_wisuda',
        'alamat',
        'no_telepon',
        'foto_profil',
        'status_verifikasi',
        'prodi_id',
        'kota_id',
    ];

    public function jawaban_kuesioner()
    {
        return $this->hasMany(Jawaban_kuesioner::class, 'alumni_id', 'id');
    }
    
    public function prodi(){
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function kota(){
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function getFotoProfileAttribute($value)
    {
        return $value ?: 'images/user.png';
    }
}

