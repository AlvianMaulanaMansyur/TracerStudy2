<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';

    protected $fillable = [
        'nama_admin',
        'nip',
        'password',
        'prodi_id',
    ];

    protected $hidden = [
        'password',
    ];
}
