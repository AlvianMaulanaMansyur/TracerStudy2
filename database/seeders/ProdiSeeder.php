<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        // Assuming the first Jurusan has an ID of 1
        Prodi::create([
            'nama_prodi' => 'Teknologi Rekayasan Perangkat Lunak',
            'jurusan_id' => 1, // Make sure this matches the ID in JurusanSeeder
        ]);

        // Add more study programs if needed
    }
}
