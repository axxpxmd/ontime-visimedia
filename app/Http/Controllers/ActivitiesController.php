<?php

namespace App\Http\Controllers;

use App\Activitie;
use App\User;
use App\Exports\ActivitieExport;
use App\Exports\UsersActivitieExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(1);
        $activities = Activitie::whereTanggal(date('Y-m-d'))->paginate(10);
        $masuk = Activitie::whereTanggal(date('Y-m-d'))->whereKeterangan('masuk')->count();
        $telat = Activitie::whereTanggal(date('Y-m-d'))->whereKeterangan('telat')->count();
        $cuti = Activitie::whereTanggal(date('Y-m-d'))->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereTanggal(date('Y-m-d'))->whereKeterangan('alpha')->count();
        $rank = $activities->firstItem();
        return view('Activities.index', compact('activities','rank','masuk','telat','cuti','alpha'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activities.create');
    }

    public function search(Request $request)
    {
        $request->validate([
            'tanggal' => ['required']
        ]);
        $activities = Activitie::whereTanggal($request->tanggal)->orderBy('jam_masuk')->paginate(10);
        $masuk = Activitie::whereTanggal($request->tanggal)->whereKeterangan('masuk')->count();
        $telat = Activitie::whereTanggal($request->tanggal)->whereKeterangan('telat')->count();
        $cuti = Activitie::whereTanggal($request->tanggal)->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereTanggal($request->tanggal)->whereKeterangan('alpha')->count();
        $rank = $activities->firstItem();
        return view('activities.index', compact('activities','rank','masuk','telat','cuti','alpha'));
    }

    public function cari(Request $request, User $user)
    {
        $request->validate([
            'bulan' => ['required']
        ]);
        $data = explode('-',$request->bulan);
        $Activities = Activitie::whereUserId($user->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->orderBy('tanggal','desc')->paginate(5);
        $masuk = Activitie::whereUserId($user->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('masuk')->count();
        $telat = Activitie::whereUserId($user->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('telat')->count();
        $cuti = Activitie::whereUserId($user->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereUserId($user->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('alpha')->count();
        $kehadiran = Activitie::whereUserId($user->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('telat')->get();
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
        return view('users.show', compact('Activities','user','masuk','telat','cuti','alpha','libur','totalJamTelat'));
    }

    public function cariDaftarHadir(Request $request)
    {
        $request->validate([
            'bulan' => ['required']
        ]);
        $data = explode('-',$request->bulan);
        $Activities = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->orderBy('tanggal','desc')->paginate(5);
        $masuk = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('masuk')->count();
        $telat = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('telat')->count();
        $cuti = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',$data[1])->whereYear('tanggal',$data[0])->whereKeterangan('alpha')->count();
        return view('Activities.show', compact('Activities','masuk','telat','cuti','alpha'));
    }

    public function checkIn(Request $request)
    {
        $users = User::all();
        $alpha = false;

        if (date('l') == 'Saturday' || date('l') == 'Sunday') {
            return redirect()->back()->with('error','Hari Libur Tidak bisa Check In');
        }

        foreach ($users as $user) {
            $absen = Activitie::whereUserId($user->id)->whereTanggal(date('Y-m-d'))->first();
            if (!$absen) {
                $alpha = true;
            }
        }

        if ($alpha) {
            foreach ($users as $user) {
                if ($user->id != $request->user_id) {
                    Activitie::create([
                        'keterangan'    => 'Alpha',
                        'tanggal'       => date('Y-m-d'),
                        'user_id'       => $user->id
                    ]);
                }
            }
        }

        $Activitie = Activitie::whereUserId($request->user_id)->whereTanggal(date('Y-m-d'))->first();
        if ($Activitie) {
            if ($Activitie->keterangan == 'Alpha') {
                $data['jam_masuk']  = date('H:i:s');
                $data['tanggal']    = date('Y-m-d');
                $data['user_id']    = $request->user_id;
                if (strtotime($data['jam_masuk']) >= strtotime('07:00:00') && strtotime($data['jam_masuk']) <= strtotime('08:00:00')) {
                    $data['keterangan'] = 'Masuk';
                } else if (strtotime($data['jam_masuk']) > strtotime('08:00:00') && strtotime($data['jam_masuk']) <= strtotime('17:00:00')) {
                    $data['keterangan'] = 'Telat';
                } else {
                    $data['keterangan'] = 'Alpha';
                }
                $Activitie->update($data);
                return redirect()->back()->with('success','Check-in berhasil');
            } else {
                return redirect()->back()->with('error','Check-in gagal');
            }
        }

        $data['jam_masuk']  = date('H:i:s');
        $data['tanggal']    = date('Y-m-d');
        $data['user_id']    = $request->user_id;
        if (strtotime($data['jam_masuk']) >= strtotime('07:00:00') && strtotime($data['jam_masuk']) <= strtotime('08:00:00')) {
            $data['keterangan'] = 'Masuk';
        } else if (strtotime($data['jam_masuk']) > strtotime('08:00:00') && strtotime($data['jam_masuk']) <= strtotime('17:00:00')) {
            $data['keterangan'] = 'Telat';
        } else {
            $data['keterangan'] = 'Alpha';
        }

        Activitie::create($data);
        return redirect()->back()->with('success','Check-in berhasil');
    }

    public function checkOut(Request $request, Activitie $kehadiran)
    {
        $data['jam_keluar'] = date('H:i:s');
        $kehadiran->update($data);
        return redirect()->back()->with('success', 'Check-out berhasil');
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
        $Activities = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->orderBy('tanggal','desc')->paginate(10);
        $masuk = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('masuk')->count();
        $telat = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('telat')->count();
        $cuti = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('cuti')->count();
        $alpha = Activitie::whereUserId(auth()->user()->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('alpha')->count();
        return view('Activities.show', compact('Activities','masuk','telat','cuti','alpha'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activitie  $kehadiran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activitie $kehadiran)
    {
        $data = $request->validate([
            'keterangan'    => ['required']
        ]);

        if ($request->jam_keluar) {
            $data['jam_keluar'] = $request->jam_keluar;
        }

        if ($request->keterangan == 'Masuk' || $request->keterangan == 'Telat') {
            $data['jam_masuk'] = $request->jam_masuk;
            if (strtotime($data['jam_masuk']) >= strtotime('07:00:00') && strtotime($data['jam_masuk']) <= strtotime('08:00:00')) {
                $data['keterangan'] = 'Masuk';
            } else if (strtotime($data['jam_masuk']) > strtotime('08:00:00') && strtotime($data['jam_masuk']) <= strtotime('17:00:00')) {
                $data['keterangan'] = 'Telat';
            } else {
                $data['keterangan'] = 'Alpha';
            }
        } else {
            $data['jam_masuk'] = null;
            $data['jam_keluar'] = null;
        }
        $kehadiran->update($data);
        return redirect()->back()->with('success', 'Kehadiran tanggal "'.date('l, d F Y',strtotime($kehadiran->tanggal)).'" berhasil diubah');
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
