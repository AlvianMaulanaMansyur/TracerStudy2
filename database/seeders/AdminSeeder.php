<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'nama_admin' => 'Admin Name',
            'nip' => '123456',
            'password' => Hash::make('password'),
            'prodi_id' => 1,
        ]);
    }
}
