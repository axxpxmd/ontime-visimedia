<?php

namespace App\Console\Commands;

use App\HariLibur;
use App\JamKerja;
use App\Present;
use App\LogDenda;
use App\User;
use Illuminate\Console\Command;

class Denda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hitung:denda {--month=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hitung Denda';

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
        //Total Jam
        $present = Present::whereIn('keterangan', ['Telat','Masuk'])->whereMonth('tanggal', $this->option('month'))->get();
        foreach ($present as $p) {
            $jamkerja = JamKerja::whereN(date('N', strtotime($p->tanggal)))->where('shift_id', $p->user->shift_id)->first();
            // $jamkerja = JamKerja::whereN(date('N', strtotime($p->tanggal)))->first();
            if ($jamkerja) {
                if ($p->jam_keluar != '') {
                    if (strtotime($p->jam_keluar) > strtotime($jamkerja->selesai_kerja)) {
                        $dt['total_jam'] = jam_total($p->jam_masuk, $jamkerja->selesai_kerja);
                        $dt['total_lembur'] = jam_total($jamkerja->selesai_kerja, $p->jam_keluar);
                    } else {
                        $dt['total_jam'] = jam_total($p->jam_masuk, $p->jam_keluar);
                        $dt['total_lembur'] = null;
                    }
                } else {
                    $dt['total_jam'] = null;
                    $dt['total_lembur'] = null;
                }

                Present::whereid($p->id)->update($dt);
                $this->info("update success Total jam :  {$p->user->nama} tanggal {$p->tanggal}");
            }
        }


        $present = Present::whereIn('keterangan', ['Masuk','Telat','Alpha'])->whereMonth('tanggal', $this->option('month'))->get();
        $this->info("Data : {$present->count()}");
        foreach ($present as $p) {
            $denda = total_sanksi2($p);
            if ($denda) {
                Present::whereid($p->id)->update(['denda' => $denda]);
                $this->info("update success :  {$p->user->nama} tanggal {$p->tanggal} denda");
            } else {
                Present::whereid($p->id)->update(['denda' => null]);
                $this->info("update success :  {$p->user->nama} tanggal {$p->tanggal} null");
                $logDenda =  LogDenda::where('present_id', $p->id)->get();
                if ($logDenda) {
                    foreach ($logDenda as $org) {
                        $org->delete();
                    }
                }
            }
        }
        $present = Present::whereNotIn('keterangan', ['Masuk','Telat','Alpha'])->whereMonth('tanggal', $this->option('month'))->get();
        foreach ($present as $p) {
            $denda = 0;
            $dt2['total_jam'] = null;
            $dt2['total_lembur'] = null;
            $dt2['denda'] = null;
            Present::whereid($p->id)->update($dt2);
            $this->info("update success :  {$p->user->nama} tanggal {$p->tanggal} null");
            $logDenda =  LogDenda::where('present_id', $p->id)->get();
            if ($logDenda) {
                foreach ($logDenda as $org) {
                    $org->delete();
                }
            }
        }
    }
}
