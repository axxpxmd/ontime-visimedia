<?php

namespace App\Http\Controllers;

use App\User;
use App\Present;
use App\JamKerja;
use Carbon\Carbon;
use App\PersonalInformation;
use Illuminate\Http\Request;
use App\Exports\PresentExport;
use App\Exports\UsersPresentExport;
use App\Sanksi;
use Maatwebsite\Excel\Facades\Excel;

class PresentsAtasanController extends Controller
{
    protected $keterangan = ['Masuk', 'Telat', 'Alpha', 'Cuti', 'Sakit', 'Izin'];
    public function lst($cari = null)
    {
        if (auth()->user()->role_id == 2) {
            $lst = PersonalInformation::join('users', 'users.id', 'personal_information.user_id')
                ->where('personal_information.subunit_kerja_id', auth()->user()->personalInformation->subunit_kerja_id)
                ->where('users.role_id', 3);
        } else if (auth()->user()->role_id == 4) {
            $lst = PersonalInformation::join('users', 'users.id', 'personal_information.user_id')
                ->where('personal_information.unit_kerja_id', auth()->user()->personalInformation->unit_kerja_id)
                ->whereIn('users.role_id', [3, 2]);
        } else if (auth()->user()->role_id == 5) {
            $lst = PersonalInformation::join('users', 'users.id', 'personal_information.user_id')
                ->where('personal_information.opd_id', auth()->user()->personalInformation->opd_id)
                ->whereIn('users.role_id', [4, 3, 2]);
        } elseif (auth()->user()->role_id == 6) {
            $lst = PersonalInformation::join('users', 'users.id', 'personal_information.user_id')
                ->where('personal_information.opd_id', auth()->user()->personalInformation->opd_id);
        } elseif (auth()->user()->role_id == 1) {
            $lst = PersonalInformation::join('users', 'users.id', 'personal_information.user_id');
        } else {
            $lst = PersonalInformation::join('users', 'users.id', 'personal_information.user_id')
                ->where('personal_information.user_id', auth()->user()->personalInformation->user_id);
        }

        if ($cari != null) {
            $lst->where('personal_information.nama', 'like', '%' . $cari . '%');
        }
        return $lst->pluck('users.id')->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->lst();
        $user[] = auth()->user()->id;
        $presents   = Present::whereIn('user_id', $user)->whereTanggal(date('Y-m-d'))->orderBy('tanggal', 'desc')->paginate(10);
        $masuk  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('masuk')->count();
        $telat  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('telat')->count();
        $izin   = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('izin')->count();
        $sakit  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('sakit')->count();
        $cuti   = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('cuti')->count();
        $alpha  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('alpha')->count();
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();
        $my = Present::whereTanggal(date('Y-m-d'))->whereuser_id(auth()->user()->id)->first();
        $tgl = Carbon::now()->isoFormat('D MMMM Y');
        $date = Carbon::now()->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $hari = $date->format('l');
        $rank = $presents->firstItem();
        $keterangan = $this->keterangan;
        $sel_keterangan = '';
        return view('atasanPresents.index', compact('presents', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'jamkerja', 'my', 'tgl', 'hari', 'rank', 'keterangan', 'sel_keterangan'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'tanggal' => ['required']
        ]);
        $user = $this->lst();
        $user[] = auth()->user()->id;
        $query = '?tanggal=' . $request->tanggal;
        $sel_uker = $request->uker ?? '';
        $sel_keterangan = $request->keterangan ?? '';
        $nama_pegawai = $request->nama_pegawai ?? '';
        $presents = Present::select('presents.*', 'users.nama')->join('users', 'users.id', '=', 'presents.user_id')->whereTanggal($request->tanggal);

        if ($sel_keterangan != '') {
            $presents = $presents->where('presents.keterangan', $sel_keterangan);
            $query .= '&keterangan=' . $sel_keterangan;
        }
        if ($nama_pegawai != '') {
            $presents = $presents->where('users.nama', 'like', '%' . $nama_pegawai . '%');
            $query .= '&nama_pegawai=' . $nama_pegawai;
        }
        $user = $this->lst();
        $user[] = auth()->user()->id;
        $presents = $presents->whereIn('user_id', $user)->orderBy('jam_masuk')->paginate(10);

        $masuk = Present::whereTanggal($request->tanggal)->whereKeterangan('masuk')->count();
        $telat = Present::whereTanggal($request->tanggal)->whereKeterangan('telat')->count();
        $izin = Present::whereTanggal($request->tanggal)->whereKeterangan('izin')->count();
        $sakit = Present::whereTanggal($request->tanggal)->whereKeterangan('sakit')->count();
        $cuti = Present::whereTanggal($request->tanggal)->whereKeterangan('cuti')->count();
        $alpha = Present::whereTanggal($request->tanggal)->whereKeterangan('alpha')->count();
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();
        $my = Present::whereTanggal(date('Y-m-d'))->whereuser_id(auth()->user()->id)->first();
        $tgl = Carbon::now()->isoFormat('D MMMM Y');
        $date = Carbon::now()->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $hari = $date->format('l');
        $rank = $presents->firstItem();
        $keterangan = $this->keterangan;
        return view('atasanPresents.index', compact('presents', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'jamkerja', 'my', 'tgl', 'hari', 'rank', 'keterangan', 'sel_keterangan', 'query', 'user'));
    }

    public function cari(Request $request, User $user)
    {
        // $request->validate([
        //     'bulan' => ['required']
        // ]);
        // $data = explode('-', $request->bulan);
        // $p = Present::whereUserId($user->id)->where('tanggal' ,'>=' , $request->periode_mulai)->where('tanggal','<=',$request->periode_selesai)->get();
        // dd($p);
        $presents = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->orderBy('tanggal', 'desc')->paginate(5);
        $masuk = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('masuk')->count();
        $telat = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('telat')->count();
        $izin = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('izin')->count();
        $sakit = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('sakit')->count();
        $cuti = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('cuti')->count();
        $alpha = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('alpha')->count();
        $kehadiran = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->whereKeterangan('telat')->get();
        $totalJamTelat = 0;
        $sanksi =  Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)->where('tanggal', '<=', $request->periode_selesai)->sum('denda');
        foreach ($kehadiran as $present) {
            $totalJamTelat = $totalJamTelat + (\Carbon\Carbon::parse($present->jam_masuk)->diffInHours(\Carbon\Carbon::parse('07:00:00')));
        }
        // $url = 'https://kalenderindonesia.com/api/YZ35u6a7sFWN/libur/masehi/'.date('Y/m');
        // $kalender = file_get_contents($url);
        // $kalender = json_decode($kalender, true);
        $libur = false;
        $holiday = null;
        // if ($kalender['data'] != false) {
        //     if ($kalender['data']['holiday']['data']) {
        //         foreach ($kalender['data']['holiday']['data'] as $key => $value) {
        //             if ($value['date'] == date('Y-m-d')) {
        //                 $holiday = $value['name'];
        //                 $libur = true;
        //                 break;
        //             }
        //         }
        //     }
        // }
        return view('atasan.show', compact('presents', 'user', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'libur', 'totalJamTelat', 'sanksi'));
    }

    // public function cariDaftarHadir(Request $request)
    // {
    //     $request->validate([
    //         'bulan' => ['required']
    //     ]);
    //     $data = explode('-',$request->bulan);
    //     $presents = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->orderBy('tanggal','desc')->paginate(5);
    //     $masuk = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('masuk')->count();
    //     $telat = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('telat')->count();
    //     $izin = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('izin')->count();
    //     $sakit = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('sakit')->count();
    //     $cuti = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('cuti')->count();
    //     $alpha = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('alpha')->count();
    //     return view('presents.show', compact('presents','masuk','telat','izin','sakit','cuti','alpha'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $present = Present::whereUserId($request->user_id)->whereTanggal(date('Y-m-d'))->first();
    //     if ($present) {
    //         return redirect()->back()->with('error','Absensi hari ini telah terisi');
    //     }
    //     $data = $request->validate([
    //         'keterangan'    => ['required'],
    //         'user_id'    => ['required']
    //     ]);
    //     $data['tanggal'] = date('Y-m-d');
    //     if ($request->keterangan == 'Masuk' || $request->keterangan == 'Telat') {
    //         $data['jam_masuk'] = $request->jam_masuk;
    //         if (strtotime($data['jam_masuk']) >= strtotime('07:00:00') && strtotime($data['jam_masuk']) <= strtotime('08:00:00')) {
    //             $data['keterangan'] = 'Masuk';
    //         } else if (strtotime($data['jam_masuk']) > strtotime('08:00:00') && strtotime($data['jam_masuk']) <= strtotime('17:00:00')) {
    //             $data['keterangan'] = 'Telat';
    //         } else {
    //             $data['keterangan'] = 'Alpha';
    //         }
    //     }
    //     Present::create($data);
    //     return redirect()->back()->with('success','Kehadiran berhasil ditambahkan');
    // }

    // public function ubah(Request $request)
    // {
    //     $present = Present::findOrFail($request->id);
    //     echo json_encode($present);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $presents = Present::find($id);
        $users = User::all();

        return view('atasanPresents.edit', compact('presents', 'users'));
    }

    public function update($id, Request $request)
    {
        $presents = Present::find($id);

        $user  = User::where('id', $presents->user_id)->first();
        $gaji  = ($user->sallary ? $user->sallary : 0) * (50 / 100);
        $sanksi_id = $user->tmsanksi_id;

        $telatPercent = Sanksi::select('percent', 'id')->where('tmsanksi_id', $sanksi_id)->first();
        $denda = $gaji * ($telatPercent->percent / 100);

        if (isset($_POST['setujui'])) {

            $data['status_permohonan'] = 1;
            $data['keterangan_atasan'] = $request->keterangan_atasan;
            $data['denda'] = Null;
            $presents->update($data);

            return redirect()->back()->with('success', 'Absensi berhasil disetujui');
        } elseif (isset($_POST['tolak'])) {

            $data['status_permohonan'] = 2;
            $data['keterangan_atasan'] = $request->keterangan_atasan;
            $data['denda'] = $denda;
            $presents->update($data);

            return redirect()->back()->with('success', 'Absensi berhasil ditolak');
        }
    }
}
