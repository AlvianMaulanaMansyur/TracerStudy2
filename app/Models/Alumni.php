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
    
    public function prodi(){
        return $this->belongsTo(Prodi::class);
    }

    public function kota(){
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
