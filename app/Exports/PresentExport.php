<?php

namespace App\Exports;

use App\User;
use App\Present;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresentExport implements FromView
{
    private $user_id;
    private $periode_mulai;
    private $periode_selesai;

    public function __construct($user_id, $periode_mulai, $periode_selesai)
    {
        $this->user_id = $user_id;
        $this->periode_mulai = $periode_mulai;
        $this->periode_selesai = $periode_selesai;
    }

    public function view(): view
    {
        $data = explode('-', $this->periode_mulai);
        $periode_mulai = $this->periode_mulai;
        $periode_selesai = $this->periode_selesai;
        $presents = Present::whereUserId($this->user_id)->where('tanggal', '>=', $this->periode_mulai)->where('tanggal', '<=', $this->periode_selesai)->orderBy('tanggal', 'asc')->get();
        $kehadiran = Present::whereUserId($this->user_id)->where('tanggal', '>=', $this->periode_mulai)->where('tanggal', '<=', $this->periode_selesai)->whereKeterangan('telat')->get();
        $user = User::whereid($this->user_id)->first();
        $totalJamTelat = 0;
        foreach ($kehadiran as $present) {
            $totalJamTelat = $totalJamTelat + (\Carbon\Carbon::parse($present->jam_masuk)->diffInHours(\Carbon\Carbon::parse('07:30:00')));
        }

        if ($user->personalInformation->subunit_kerja_id) {
            $unit_kerja = $user->personalInformation->SubUnitKerja->nama;
        } elseif ($user->personalInformation->unit_kerja_id) {
            $unit_kerja = $user->personalInformation->UnitKerja->nama;
        } elseif ($user->personalInformation->opd_id) {
            $unit_kerja = $user->personalInformation->Opd->nama;
        } else {
            $unit_kerja = "-";
        }
        $gaji = $user->personalInformation->gaji ?? 0;
        $total_potongan= $presents->sum('denda') ?? 0;
        $total_terima = $gaji - $total_potongan;


        return view('presents.excel-user', compact('presents', 'totalJamTelat', 'user', 'periode_mulai', 'periode_selesai', 'unit_kerja', 'gaji', 'total_potongan', 'total_terima'));
    }
}
