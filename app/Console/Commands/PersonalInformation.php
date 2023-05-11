<?php

namespace App\Console\Commands;

use App\HariLibur;
use App\JamKerja;
use App\Present;
use App\User;
use Illuminate\Console\Command;

class PersonalInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'personal:information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Personal Information';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::all();
        foreach ($user as $v) {
            
        }
    }
}
