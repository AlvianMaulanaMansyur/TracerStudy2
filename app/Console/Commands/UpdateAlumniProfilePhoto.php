<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumni;

class UpdateAlumniProfilePhoto extends Command
{
    protected $signature = 'alumni:update-profile-photo';
    protected $description = 'Update default profile photo for alumni';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $defaultPhoto = 'images/user.png';
        Alumni::whereNull('foto_profil')->update(['foto_profil' => $defaultPhoto]);

        $this->info('All alumni with null profile photo have been updated.');
    }
}

