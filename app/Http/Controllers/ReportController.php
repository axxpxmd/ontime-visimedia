<?php

namespace App\Http\Controllers;

use App\Opd;
use App\User;


use App\Unitkerja;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Exports\PresentExport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function api(Request $request)
    {
        $data = DB::table('users')
                        ->select(DB::raw('users.id as user_id ,users.nama,sum(presents.denda) as denda,users.id,shifts.name as jam_kerja,users.foto as foto,users.sallary'))
                        ->join('personal_information', 'personal_information.user_id', 'users.id')

                        ->join('presents', 'presents.user_id', 'users.id')
                        ->join('unitkerjas', 'unitkerjas.id', 'users.uker_id')
                        ->join('shifts', 'shifts.id', 'users.shift_id')
                        ->whereBetween('presents.tanggal', [$request->periode_mulai,$request->periode_selesai])
                        ->whereNotNull('users.sallary')
                        ->where('users.s_akun', 1);
        if ($request->subunit_kerja_id) {
            $data = $data->where('personal_information.subunit_kerja_id', $request->subunit_kerja_id);
        }
        if ($request->unit_kerja_id) {
            $data = $data->where('personal_information.unit_kerja_id', $request->unit_kerja_id);
        }
        if ($request->opd_id) {
            $data = $data->where('personal_information.opd_id', $request->opd_id);
        }




        $data = $data->groupBy('presents.user_id')
        ->orderBy('unitkerjas.unit_kerja', 'ASC')
                        ->orderBy('users.nama', 'ASC')
                        // ->limit(50)
                        ->get();


        return DataTables::of($data)
        ->addColumn('uker', function ($p) {
            return getUnitKerja($p->user_id);
        })
        ->addColumn('detail_absen', function ($p) use ($request) {
            return "<a href='".route('kehadiran.reportDetail', ['user_id' => $p->id,'periode_mulai'=>$request->periode_mulai,'periode_selesai' => $request->periode_selesai])."'   title='Detail Absen'><i class='fas fa-download '></i></a>";
        })
        ->editColumn('yang_dibayarkan', function ($p) {
            return rupiah($p->sallary - $p->denda);
        })
        ->editColumn('sallary', function ($p) {
            return rupiah($p->sallary);
        })
            ->editColumn('denda', function ($p) {
                return rupiah($p->denda);
            })



            ->rawColumns(['nama','foto','detail_absen'])
            ->toJson();
    }
    public function index(Request $request)
    {
        $ukers = Unitkerja::all();
        $opds = getWho()['opds'];
        return view('report.index', compact('opds'));
    }
    public function excel(Request $request)
    {
        // return Excel::download(new PresentExport($request->bulan), 'kehadiran-'.$user->nrp.'-'.$request->bulan.'.xlsx');
        return Excel::download(new ReportExport($request->periode_mulai, $request->periode_selesai, $request->opd_id, $request->unit_kerja_id, $request->subunit_kerja_id, ), 'report-'.$request->periode_mulai.'-'.$request->periode_selesai.'.xlsx');
    }
}
