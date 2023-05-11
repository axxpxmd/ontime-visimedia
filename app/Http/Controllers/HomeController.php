<?php

namespace App\Http\Controllers;

use App\Console\Commands\PersonalInformation;
use App\HariLibur;
use App\JamKerja;
use App\Present;
use App\Location;
use App\LogDenda;
use App\PersonalInformation as AppPersonalInformation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index_backup()
    {
        $present = Present::whereUserId(auth()->user()->id)->whereTanggal(date('Y-m-d'))->first();

        $isHoliday = HariLibur::isHoliday();
        // dd($isHoliday);

        $libur = false;
        $holiday = null;
        if ($isHoliday["libur"]) {
            $libur = true;
            $holiday = $isHoliday["dt"]->nama;
        }


        $lokasi = DB::table('users')
            ->select('lokasi_id')
            ->where('id', '=', auth()->user()->id)
            ->first();


        $latlong = Location::all();
        $json   = json_encode($lokasi, true);



        $hasil  = strlen($json);
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();

        // $lokasi = Auth::user()->lokasi_id;
        // $json_lokasi   = json_encode($lokasi, true);
        // dd($json_lokasi);

        // $ipaddress = '';
        // if (getenv('HTTP_CLIENT_IP'))
        //     {$ipaddress = getenv('HTTP_CLIENT_IP');}
        // else if(getenv('HTTP_X_FORWARDED_FOR'))
        //     {$ipaddress = getenv('HTTP_X_FORWARDED_FOR');}
        // else if(getenv('HTTP_X_FORWARDED'))
        //     {$ipaddress = getenv('HTTP_X_FORWARDED');}
        // else if(getenv('HTTP_FORWARDED_FOR'))
        //     {$ipaddress = getenv('HTTP_FORWARDED_FOR');}
        // else if(getenv('HTTP_FORWARDED'))
        //     {$ipaddress = getenv('HTTP_FORWARDED');}
        // else if(getenv('REMOTE_ADDR'))
        //     {$ipaddress = getenv('REMOTE_ADDR');}
        // else
        //     {$ipaddress = 'UNKNOWN';}

        // $ipaddress = '103.10.66.72'; /* Static IP address */
        // $currentUserInfo = \Location::get();
        // $currentUserInfo = \Request::ip();

        return view('home', compact('present', 'libur', 'holiday', 'lokasi', 'json', 'hasil', 'latlong', 'jamkerja'));
    }
    public function index(Request $request)
    {
        $present = Present::whereUserId(auth()->user()->id)->whereTanggal(date('Y-m-d'))->first();

        $isHoliday = HariLibur::isHoliday();
        // dd($isHoliday);

        $libur = false;
        $holiday = null;
        if ($isHoliday["libur"]) {
            $libur = true;
            $holiday = $isHoliday["dt"]->nama;
        }


        $lokasi = DB::table('users')
            ->select('lokasi_id')
            ->where('id', '=', auth()->user()->id)
            ->first();


        $latlong = Location::all();
        $json   = json_encode($lokasi, true);



        $hasil  = strlen($json);
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();

        $area_checkin = User::getLokasi(Auth::user()->id);
        $area_checkin   = json_encode($area_checkin, true);
        // dd($area_checkin);

        // $ipaddress = '';
        // if (getenv('HTTP_CLIENT_IP'))
        //     {$ipaddress = getenv('HTTP_CLIENT_IP');}
        // else if(getenv('HTTP_X_FORWARDED_FOR'))
        //     {$ipaddress = getenv('HTTP_X_FORWARDED_FOR');}
        // else if(getenv('HTTP_X_FORWARDED'))
        //     {$ipaddress = getenv('HTTP_X_FORWARDED');}
        // else if(getenv('HTTP_FORWARDED_FOR'))
        //     {$ipaddress = getenv('HTTP_FORWARDED_FOR');}
        // else if(getenv('HTTP_FORWARDED'))
        //     {$ipaddress = getenv('HTTP_FORWARDED');}
        // else if(getenv('REMOTE_ADDR'))
        //     {$ipaddress = getenv('REMOTE_ADDR');}
        // else
        //     {$ipaddress = 'UNKNOWN';}

        // $ipaddress = '103.10.66.72'; /* Static IP address */
        // $currentUserInfo = \Location::get();
        // $currentUserInfo = \Request::ip();
        $onclick = $request->c == 1 ? 1 : 0;

        return view('home', compact('present', 'libur', 'holiday', 'lokasi', 'json', 'hasil', 'latlong', 'jamkerja', 'area_checkin', 'onclick'));
    }

    public function index2(Request $request)
    {
        return redirect()->route('profil');
    }

    public function dashboard(Request $request)
    {
        $bulan = ['Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Augustus',
        'September',
        'Oktober',
        'November',
        'Desember'];
        $sel_opd = $request->opd_id ?? '';
        if (auth()->user()->role_id == 7) {
            $sel_opd = auth()->user()->personalInformation->opd_id;
        }
        $usr = [];
        if ($sel_opd) {
            $usr = AppPersonalInformation::where('opd_id', $sel_opd)->pluck('user_id')->toArray();
        }

        $user = User::whereNotNull('sallary')->where('s_akun', 1);
        if ($usr) {
            $user = $user->whereIn('id', $usr);
        }
        $user = $user->get();
        $pagu = $user->sum('sallary');
        //total potongan gaji per bulan
        $opds = getWho()['opds'];

        $sel_bulan = $request->bulan ?? date('m');
        $total_denda = Present::whereMonth('tanggal', $sel_bulan)->whereYear('tanggal', date('Y'));
        if ($usr) {
            $total_denda = $total_denda->whereIn('user_id', $usr);
        }
        $total_denda = $total_denda->sum('denda');
        // dd($total_denda);
        $sanksi_denda = $total_denda;
        $realisasi  = $pagu - $total_denda;


        // $all = $pagu + $sanksi_denda + $realisasi;
        if ($pagu == 0) {
            $chart1 = json_encode([

                ["name" => "Realisasi Sallary", "y" => 0],
                ["name" => "Sanksi Denda", "y" => 0],



            ]);
        } else {
            $chart1 = json_encode([

                ["name" => "Realisasi Sallary", "y" => $realisasi / $pagu * 100],
                ["name" => "Sanksi Denda", "y" => $total_denda / $pagu * 100],



            ]);
        }

        $kategori = ["Alpha","Telat","Pulang Cepat"];
        $chart2=[];
        foreach ($kategori as $key =>$value) {
            $data = [];

            for ($i=1; $i <=12 ; $i++) {
                if ($value == "Telat") {
                    $f = ["Sanksi 1","Sanksi 2"];
                } else {
                    $f = [$value];
                }
                $denda = DB::table('presents')
                            ->join('log_denda', 'presents.id', 'log_denda.present_id')
                            ->whereMonth('tanggal', $i)->whereYear('tanggal', date('Y'))
                            ->whereIn('log_denda.kategori', $f);
                if ($usr) {
                    $denda = $denda->whereIn('presents.user_id', $usr);
                }
                $denda = $denda->sum('log_denda.denda');
                $data[] = $denda;
            }
            $chart2[]=[
                "name" => $kategori[$key],
                "data" => $data,

            ];
        }

        $chart2 = json_encode($chart2);
        return view('dashboard', compact('opds', 'pagu', 'sanksi_denda', 'realisasi', 'chart1', 'chart2', 'bulan', 'sel_bulan', 'sel_opd'));
    }

    public function api_dashboard(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $sel_opd = $request->opd_id ?? '';
        if (auth()->user()->role_id == 7) {
            $sel_opd = auth()->user()->personalInformation->opd_id;
        }
        $usr = [];
        if ($sel_opd) {
            $usr = AppPersonalInformation::where('opd_id', $sel_opd)->pluck('user_id')->toArray();
        }
        $data = DB::table('users')
                        ->select(DB::raw('users.id as user_id,users.nama,sum(presents.denda) as denda,users.id,shifts.name as jam_kerja,users.foto as foto'))
                        ->join('presents', 'presents.user_id', 'users.id')
                        // ->join('unitkerjas', 'unitkerjas.id', 'users.uker_id')
                        ->join('shifts', 'shifts.id', 'users.shift_id')
                        ->whereMonth('presents.tanggal', $bulan)->whereYear('presents.tanggal', date('Y'))
                        ->whereNotNull('users.sallary')
                        ->where('denda', '>', 0);
        if ($sel_opd) {
            $data = $data->whereIn('presents.user_id', $usr);
        }
        $data = $data->groupBy('presents.user_id')
                        ->orderBy('denda', 'DESC')
                        // ->limit(50)
                        ->get();



        return DataTables::of($data)
            ->addColumn('uker', function ($p) {
                return getUnitKerja($p->user_id);
            })
            ->editColumn('denda', function ($p) {
                return rupiah($p->denda);
            })
            ->editColumn('nama', function ($p) {
                return "<a href='".route('users.show', $p->id)."'   title='Lihat Pegawai'><i class='fas fa-eyes '></i>".$p->nama."</a>";
            })
            ->editColumn('foto', function ($p) {
                if (Storage::disk('sftp')->exists($p->foto)) {
                    $foto = config('app.ftp_src') . $p->foto;
                } else {
                    $foto =  config('app.ftp_src') . 'default.png';
                }

                return '<a data-fancybox data-src="'.$foto.'">
                <img src="'.$foto.'" width="50px" height="50px" />
            </a>';
            })



            ->rawColumns(['nama','foto'])
            ->toJson();
    }
}
