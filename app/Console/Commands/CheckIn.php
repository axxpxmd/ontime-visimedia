<?php

namespace App\Console\Commands;

use App\HariLibur;
use App\JamKerja;
use App\Present;
use App\Sanksi;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Check:in';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Present In Initial';

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
        $keterangan = "Alpha";

        if ((date('N', strtotime(date('Y-m-d'))) >= 6)) {
            $keterangan = "Libur";
        }

        $libur = HariLibur::pluck('tgl')->toArray();
        if (in_array($date, $libur)) {
            $keterangan = "Libur";
        }

        $users = User::all();
        foreach ($users as $u) {
            Present::firstOrCreate([
                'tanggal'       => date('Y-m-d'),
                'user_id'       => $u->id,
                'keterangan'    => $keterangan
            ]);
        }

        // Denda tidak absen pulang
        $kemaren  = Carbon::now()->subDays(1)->format('Y-m-d');
        $presents = Present::where('tanggal', $kemaren)->whereIn('keterangan', ['Masuk', 'Telat'])->whereNull('jam_keluar')->get();

        foreach ($presents as $p) {
            $user  = User::where('id', $p->user_id)->first();
            $gaji  = ($user->sallary ? $user->sallary : 0) * (50 / 100);
            $sanksi_id = $user->tmsanksi_id;

            $telatPercent = Sanksi::select('percent', 'id')->where('tmsanksi_id', $sanksi_id)->first();
            $denda = $gaji * ($telatPercent->percent / 100);

            Present::find($p->id)->update([
                'keterangan' => 'Alpha',
                'keterangan_atasan' => 'tidak absen pulang',
                'denda' => $denda
            ]);
        }
    }
}
