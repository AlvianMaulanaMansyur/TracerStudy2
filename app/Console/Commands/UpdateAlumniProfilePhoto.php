<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumni;

class UpdateAlumniProfilePhoto extends Command
{
    protected $signature = 'alumni:update-profile-photo';
    protected $description = 'Update all alumni profile photos to the default';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $defaultPhoto = 'alumni_foto_profil/user.png'; // Menggunakan path dari storage
        Alumni::all()->each(function ($alumni) use ($defaultPhoto) {
            $alumni->update(['foto_profil' => $defaultPhoto]); // Memanggil metode update dengan instance model
        });

        $this->info('All alumni profile photos have been updated to the default.');
    }
}
