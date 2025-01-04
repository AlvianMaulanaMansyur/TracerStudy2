<?php

namespace Database\Factories;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumni>
 */
class AlumniFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Alumni::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nim' => $this->faker->unique()->numerify('##########'),
            'nama_alumni' => $this->faker->name,
            'npwp' => $this->faker->numerify('################'),
            'nik' => $this->faker->numerify('################'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // Menggunakan bcrypt untuk password
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'angkatan' => $this->faker->numberBetween(2010, 2023),
            'tahun_lulus' => $this->faker->numberBetween(2014, 2023),
            'gelombang_wisuda' => $this->faker->numberBetween(1, 3),
            'alamat' => $this->faker->address,
            'no_telepon' => $this->faker->phoneNumber,
            'foto_profil' => 'images/user.png', // Menghasilkan URL gambar
            'status_verifikasi' => $this->faker->numberBetween(0, 1),
            'prodi_id' => 1, // Sesuaikan dengan ID prodi yang ada
            'kota_id' => 1, // Sesuaikan dengan ID kota yang ada
        ];
    }
}
