<?php

namespace Database\Seeders;

use App\Models\Alumni;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class AlumniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Alumni::create([
        //     'nim' => '2215354037',
        //     'nama_alumni' => 'Asep',
        //     'npwp' => '1234567890123456',
        //     'nik' => '1234567890123456',
        //     'email' => 'asep@gmail.com',
        //     'password' => Hash::make('password'), // Menggunakan bcrypt untuk password
        //     'jenis_kelamin' => 'L',
        //     'angkatan' => 2019,
        //     'tahun_lulus' => 2023,
        //     'gelombang_wisuda' => 1,
        //     'alamat' => 'alamanataa',
        //     'no_telepon' => '10341034810841',
        //     'foto_profil' => 'images/user.png', // Menghasilkan URL gambar
        //     'status_verifikasi' => 1,
        //     'prodi_id' => 1,
        //     'kota_id' => 1, // Sesuaikan dengan ID kota yang ada
        // ]);

        // Alumni::factory()->count(50)->create();
    }
}
