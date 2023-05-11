<?php

namespace App\Console\Commands;

use App\JamKerja;
use App\Present;
use Illuminate\Console\Command;

class CheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Check:out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate Checkout';

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
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $jamkerja = JamKerja::whereN(date('N'))->first();
        $present = Present::wheretanggal($date)->whereIn('keterangan',['Masuk','Telat'])->whereNull('jam_keluar')->get();
        if(date('N') != 6 || date('N') != 7){
            foreach ($present as $p) {
                $total_jam = jam_total($p->jam_masuk,$jamkerja->selesai_kerja);
                $p->update([
                    'jam_keluar' => $jamkerja->selesai_kerja,
                    'total_jam' => $total_jam,
                ]);
            }
        }



    }
}
