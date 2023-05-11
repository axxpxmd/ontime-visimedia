<?php

namespace App\Exports;

use App\Opd;
use App\Present;
use App\SubUnitKerja;
use App\Unitkerja;
use App\UnitKerjaa;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    private $periode_mulai;
    private $periode_selesai;
    private $opd_id;
    private $unit_kerja_id;
    private $subunit_kerja_id;

    public function __construct($periode_mulai, $periode_selesai, $opd_id='', $unit_kerja_id='', $subunit_kerja_id='')
    {
        $this->periode_mulai = $periode_mulai;
        $this->periode_selesai = $periode_selesai;
        $this->opd_id = $opd_id;
        $this->unit_kerja_id = $unit_kerja_id;
        $this->subunit_kerja_id = $subunit_kerja_id;
    }

    public function view(): view
    {
        $periode_mulai = $this->periode_mulai;
        $periode_selesai = $this->periode_selesai;
        $opd_id = $this->opd_id;
        $unit_kerja_id = $this->unit_kerja_id;
        $subunit_kerja_id = $this->subunit_kerja_id;
        $data = DB::table('users')
                        ->select(DB::raw('users.nama,sum(presents.denda) as denda,users.id as user_id,unitkerjas.unit_kerja as uker,shifts.name as jam_kerja,users.foto as foto,users.sallary'))
                        ->join('presents', 'presents.user_id', 'users.id')
                        ->join('personal_information', 'personal_information.user_id', 'users.id')
                        ->join('unitkerjas', 'unitkerjas.id', 'users.uker_id')
                        ->join('shifts', 'shifts.id', 'users.shift_id')
                        ->whereBetween('presents.tanggal', [$this->periode_mulai,$this->periode_selesai])
                        ->whereNotNull('users.sallary');
        if ($this->subunit_kerja_id) {
            $data = $data->where('personal_information.subunit_kerja_id', $this->subunit_kerja_id);
        }
        if ($this->unit_kerja_id) {
            $data = $data->where('personal_information.unit_kerja_id', $this->unit_kerja_id);
        }
        if ($this->opd_id) {
            $data = $data->where('personal_information.opd_id', $this->opd_id);
        }





        $data = $data->groupBy('presents.user_id')
        ->orderBy('unitkerjas.unit_kerja', 'ASC')
                        ->orderBy('users.nama', 'ASC')
                        // ->limit(50)
                        ->get();
        foreach ($data as $k => $i) {
            $data[$k]->uker = getUnitKerja($i->user_id);
        }
        $opd =  $opd_id ? Opd::whereid($opd_id)->first()->nama:'Semua';
        $unit_kerja =  $unit_kerja_id ? UnitKerjaa::whereid($unit_kerja_id)->first()->nama:'Semua';
        $subunit_kerja =  $subunit_kerja_id ? SubUnitKerja::whereid($subunit_kerja_id)->first()->nama:'Semua';
        // dd([$opd_id,$unit_kerja_id,$subunit_kerja_id]);

        return view('report.excel-export', compact('data', 'periode_mulai', 'periode_selesai', 'unit_kerja', 'opd', 'subunit_kerja'));
    }
}
