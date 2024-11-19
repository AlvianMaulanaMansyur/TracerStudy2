<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        Jurusan::create([
            'nama_jurusan' => 'Teknologi Informasi',
        ]);

        // Add more departments if needed
    }
}
