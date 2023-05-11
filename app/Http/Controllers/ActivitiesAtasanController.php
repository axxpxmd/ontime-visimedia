<?php

namespace App\Http\Controllers;

use App\Activitie;
use App\User;
use App\Exports\ActivitieExport;
use App\Exports\UsersActivitieExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivitiesAtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::whereatasan_id(Auth::user()->id)->pluck('id')->toArray();
        $user[] = Auth::user()->id;
        $activities = Activitie::whereIn('user_id', $user)->where('tanggal', 'like', date('Y-m-%'))->with(['user:id,nama'])->paginate(10);

        $rank = $activities->firstItem();
        return view('atasanActivities.index', compact('activities', 'rank'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('atasanActivities.create');
    }

    public function search(Request $request)
    {
        $request->validate([
            'tanggal' => ['required']
        ]);
        $user = User::whereatasan_id(Auth::user()->id)->pluck('id')->toArray();
        $user[] = Auth::user()->id;

        $activities = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->paginate(10);
        $masuk = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->whereKeterangan('masuk')->count();
        $telat = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->whereKeterangan('telat')->count();
        $izin = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->whereKeterangan('izin')->count();
        $sakit = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->whereKeterangan('sakit')->count();
        $cuti = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereIn('user_id', $user)->whereTanggal($request->tanggal)->whereKeterangan('alpha')->count();
        $rank = $activities->firstItem();
        return view('atasanActivities.index', compact('activities', 'rank', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha'));
    }

    public function cari(Request $request, User $user)
    {
        $request->validate([
            'bulan' => ['required']
        ]);
        $data = explode('-', $request->bulan);
        $Activities = Activitie::whereUserId($user->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->orderBy('tanggal', 'desc')->paginate(5);
        $masuk = Activitie::whereUserId($user->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('masuk')->count();
        $telat = Activitie::whereUserId($user->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('telat')->count();
        $cuti = Activitie::whereUserId($user->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereUserId($user->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('alpha')->count();
        $kehadiran = Activitie::whereUserId($user->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('telat')->get();
        $totalJamTelat = 0;
        foreach ($kehadiran as $Activitie) {
            $totalJamTelat = $totalJamTelat + (\Carbon\Carbon::parse($Activitie->jam_masuk)->diffInHours(\Carbon\Carbon::parse('07:00:00')));
        }
        $url = 'https://kalenderindonesia.com/api/YZ35u6a7sFWN/libur/masehi/'.date('Y/m');
        $kalender = file_get_contents($url);
        $kalender = json_decode($kalender, true);
        $libur = false;
        $holiday = null;
        if ($kalender['data'] != false) {
            if ($kalender['data']['holiday']['data']) {
                foreach ($kalender['data']['holiday']['data'] as $key => $value) {
                    if ($value['date'] == date('Y-m-d')) {
                        $holiday = $value['name'];
                        $libur = true;
                        break;
                    }
                }
            }
        }
        return view('users.show', compact('Activities', 'user', 'masuk', 'telat', 'cuti', 'alpha', 'libur', 'totalJamTelat'));
    }

    public function cariDaftarHadir(Request $request)
    {
        $request->validate([
            'bulan' => ['required']
        ]);
        $data = explode('-', $request->bulan);
        $Activities = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->orderBy('tanggal', 'desc')->paginate(5);
        $masuk = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('masuk')->count();
        $telat = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('telat')->count();
        $cuti = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('alpha')->count();
        return view('Activities.show', compact('Activities', 'masuk', 'telat', 'cuti', 'alpha'));
    }

    public function edit($id)
    {
        $activities = Activitie::find($id);
        return view('atasanActivities.edit', compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tgl_aktifitas' => ['required', 'date'],
            'aktifitas'     => ['required', 'string'],
            'jam_mulai'     => ['required'],
            'jam_selesai'   => ['required'],
            'keterangan'    => ['required', 'string'],
            'foto'          => ['image', 'mimes:jpeg,png,gif', 'max:2048']
        ]);

        $data['user_id']        = auth()->user()->id;
        $data['tanggal']        = $request->tgl_aktifitas;
        $data['aktifitas']      = $request->aktifitas;
        $data['jam_mulai']      = $request->jam_mulai;
        $data['jam_selesai']    = $request->jam_selesai;
        $data['keterangan']     = $request->keterangan;
        $data['c_verifikasi']   = 0;

        if ($request->file('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-aktifitas');
        } else {
            $data['foto'] = 'default.jpg';
        }

        Activitie::create($data);
        // return redirect()->back()->with('success','Aktifitas berhasil ditambahkan');
        return redirect('/activities')->with('success', 'Aktifitas berhasil ditambahkan');
    }

    public function ubah(Request $request)
    {
        $Activitie = Activitie::findOrFail($request->id);
        echo json_encode($Activitie);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $Activities = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('tanggal', 'desc')->paginate(10);
        $presents = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('tanggal', 'desc')->paginate(10);
        $masuk = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('masuk')->count();
        $telat = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('telat')->count();
        $cuti = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('alpha')->count();
        return view('atasanActivities.show', compact('presents', 'Activities', 'masuk', 'telat', 'cuti', 'alpha'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activitie  $kehadiran
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $aktifitas = Activitie::find($id);

        if (isset($_POST['setujui'])) {
            $data['c_verifikasi'] = 1;
            $aktifitas->update($data);
            return redirect()->back()->with('success', 'Aktifitas berhasil disetujui');
        } elseif (isset($_POST['tolak'])) {
            $data['c_verifikasi'] = 2;
            $aktifitas->update($data);
            return redirect()->back()->with('success', 'Aktifitas berhasil ditolak');
        }
    }

    public function excelUser(Request $request, User $user)
    {
        return Excel::download(new ActivitieExport($user->id, $request->bulan), 'kehadiran-'.$user->username.'-'.$request->bulan.'.xlsx');
        // return Excel::download(new ActivitieExport($user->id, $request->bulan), 'kehadiran-'.$user->nrp.'-'.$request->bulan.'.xlsx');
    }

    public function excelUsers(Request $request)
    {
        return Excel::download(new UsersActivitieExport($request->tanggal), 'kehadiran-'.$request->tanggal.'.xlsx');
    }
}
