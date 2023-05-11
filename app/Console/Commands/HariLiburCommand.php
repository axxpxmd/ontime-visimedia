<?php

namespace App\Console\Commands;

use App\HariLibur;
use Illuminate\Console\Command;

class HariLiburCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:hari-libur';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info("run");
        $url = 'https://kalenderindonesia.com/api/YZ35u6a7sFWN/libur/masehi/'.date('Y');

        $kalender = file_get_contents($url);
        $kalender = json_decode($kalender, true);
        if ($kalender['data'] != false) {
                if ($kalender['data']['holiday']) {
                    foreach ($kalender['data']['holiday'] as  $h) {
                        if($h['data']){
                            foreach ($h['data'] as  $d) {
                                HariLibur::FirstOrCreate([
                                    'nama' => $d['name'],
                                    'tgl' => $d['date']
                                ]);
                            }
                        }

                    }
                }
            }
            $this->info("done");
    }
}
