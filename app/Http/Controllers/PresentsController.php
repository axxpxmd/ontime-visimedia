<?php

namespace App\Http\Controllers;

use PDF;
use Image;
use App\Opd;
use App\Role;
use App\User;
use App\Present;
use App\JamKerja;
use App\Unitkerja;
use Carbon\Carbon;
use App\JenisJabatan;
use App\CurrentLocation;
use Illuminate\Http\Request;
use App\Exports\PresentExport;
use App\Exports\UsersPresentExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class PresentsController extends Controller
{
    protected $keterangan = ['Masuk', 'Telat', 'Alpha', 'Cuti', 'Sakit', 'Izin'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function config()
    {
        $auth = auth()->user()->role_id;
        $pi = auth()->user()->personalInformation;
        if ($auth == 1) {
            $data['user'] = new User();
            $data['opds'] = Opd::all();
            $data['roles'] = Role::all();
        } else {
            $data['user'] = User::select('users.*')
                ->join('personal_information', 'users.id', 'personal_information.user_id')
                ->where('personal_information.opd_id', $pi->opd_id);

            $data['opds'] = Opd::whereid($pi->opd_id)->get();
            $data['roles'] = Role::whereNotIn('id', [1, 7, 6])->get();
        }


        $data['role_id'] = auth()->user()->role_id;

        return $data;
    }
    public function index()
    {
        // dd(1);
        $presents = Present::whereTanggal(date('Y-m-d'))->orderBy('jam_masuk')->paginate(10);
        $masuk = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('masuk')->count();
        $telat = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('telat')->count();
        $izin  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('izin')->count();
        $sakit = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('sakit')->count();
        $cuti  = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('cuti')->count();
        $alpha = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('alpha')->count();
        $ukers = Unitkerja::all();
        $rank  = $presents->firstItem();
        return view('presents.index', compact('presents', 'rank', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'ukers'));
    }
    public function index2()
    {
        $us = $this->config()['user']->pluck('id')->toArray();

        $presents = Present::whereIn('keterangan', ['Masuk', 'Telat', 'Izin', 'Sakit', 'Cuti'])
            ->whereTanggal(date('Y-m-d'))
            ->whereIn('user_id', $us)
            ->orderByRaw('ISNULL(jam_masuk), jam_masuk ASC')
            ->paginate(10);


        $masuk = Present::whereTanggal(date('Y-m-d'))->whereIn('keterangan', ['masuk', 'telat'])->whereIn('user_id', $us)->count();
        $telat = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('telat')->whereIn('user_id', $us)->count();
        $izin  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('izin')->whereIn('user_id', $us)->count();
        $sakit = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('sakit')->whereIn('user_id', $us)->count();
        $cuti  = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('cuti')->whereIn('user_id', $us)->count();
        $alpha = Present::whereTanggal(date('Y-m-d'))->whereKeterangan('alpha')->whereIn('user_id', $us)->count();
        $rank  = $presents->firstItem();
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();
        $my = Present::whereTanggal(date('Y-m-d'))->whereuser_id(auth()->user()->id)->first();
        $tgl = Carbon::now()->isoFormat('D MMMM Y');
        $date = Carbon::now()->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $hari = $date->format('l');
        $ukers = Unitkerja::all();
        $keterangan = $this->keterangan;
        $sel_uker =  '';
        $sel_keterangan = '';
        $opd_id = Auth::user()->personalInformation->opd_id ? Auth::user()->personalInformation->opd_id : '';
        $query = '';

        $opds = Opd::select('id', 'nama')->get();

        return view('presents.index2', compact('presents', 'opd_id', 'opds', 'rank', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'jamkerja', 'my', 'tgl', 'hari', 'ukers', 'keterangan', 'sel_uker', 'sel_keterangan', 'query'));
    }

    public function search(Request $request)
    {
        $us = $this->config()['user']->pluck('id')->toArray();
        $request->validate([
            'tanggal' => ['required']
        ]);
        $query = '?tanggal=' . $request->tanggal;
        $sel_uker = $request->uker ?? '';
        $sel_keterangan = $request->keterangan ?? '';
        $nama_pegawai = $request->nama_pegawai ?? '';
        $opd_id = $request->opd_id ?? '';
        $presents = Present::select('presents.*', 'users.nama')->join('users', 'users.id', '=', 'presents.user_id')->whereTanggal($request->tanggal);
        if ($sel_uker != '') {
            $presents = $presents->where('users.uker_id', $sel_uker);
            $query .= '&uker=' . $sel_uker;
        }
        if ($sel_keterangan != '') {
            $presents = $presents->where('presents.keterangan', $sel_keterangan);
            $query .= '&keterangan=' . $sel_keterangan;
        }
        if ($nama_pegawai != '') {
            $presents = $presents->where('users.nama', 'like', '%' . $nama_pegawai . '%');
            $query .= '&nama_pegawai=' . $nama_pegawai;
        }
        if ($opd_id != '') {
            $presents = $presents->join('personal_information', 'personal_information.user_id', '=', 'presents.user_id')
                ->where('personal_information.opd_id', $opd_id);
        }
        $presents = $presents->whereIn('presents.user_id', $us)->orderBy('jam_masuk')->paginate(10);
        $masuk = Present::whereTanggal($request->tanggal)->whereKeterangan('masuk')->whereIn('user_id', $us)->count();
        $telat = Present::whereTanggal($request->tanggal)->whereKeterangan('telat')->whereIn('user_id', $us)->count();
        $izin = Present::whereTanggal($request->tanggal)->whereKeterangan('izin')->whereIn('user_id', $us)->count();
        $sakit = Present::whereTanggal($request->tanggal)->whereKeterangan('sakit')->whereIn('user_id', $us)->count();
        $cuti = Present::whereTanggal($request->tanggal)->whereKeterangan('cuti')->whereIn('user_id', $us)->count();
        $alpha = Present::whereTanggal($request->tanggal)->whereKeterangan('alpha')->whereIn('user_id', $us)->count();
        $rank  = $presents->firstItem();
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();
        $my = Present::whereTanggal(date('Y-m-d'))->whereuser_id(auth()->user()->id)->first();
        $tgl = Carbon::now()->isoFormat('D MMMM Y');
        $date = Carbon::now()->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $hari = $date->format('l');
        $ukers = Unitkerja::all();
        $keterangan = $this->keterangan;
        $opds = Opd::select('id', 'nama')->get();

        return view('presents.index2', compact('presents', 'rank', 'opds', 'opd_id', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'jamkerja', 'my', 'tgl', 'hari', 'ukers', 'sel_uker', 'keterangan', 'sel_keterangan', 'query'));
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
        $jenisJabatan = JenisJabatan::all();
        $tingkat_pendidikan = ['SD', 'SMP', 'SMA', 'SMK', 'S1', 'S2', 'S3'];
        return view('users.show', compact('presents', 'user', 'jenisJabatan', 'tingkat_pendidikan', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'libur', 'totalJamTelat', 'sanksi'));
    }

    public function cariDaftarHadir(Request $request)
    {
        $request->validate([
            'bulan' => ['required']
        ]);
        $data = explode('-', $request->bulan);
        $presents = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->orderBy('tanggal', 'desc')->paginate(5);
        $masuk = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('masuk')->count();
        $telat = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('telat')->count();
        $izin = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('izin')->count();
        $sakit = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('sakit')->count();
        $cuti = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('cuti')->count();
        $alpha = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', $data[1])->whereYear('tanggal', $data[0])->whereKeterangan('alpha')->count();
        $tgl = Carbon::now()->isoFormat('D MMMM Y');
        $date = Carbon::now()->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        $hari = $date->format('l');
        $my = Present::whereTanggal(date('Y-m-d'))->whereuser_id(auth()->user()->id)->first();
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();
        return view('presents.show', compact('presents', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'my', 'jamkerja'));
    }

    public function checkIn(Request $request)
    {
        $users = User::where('s_akun', 1)->get();
        $alpha = false;

        if (date('l') == 'Saturday' || date('l') == 'Sunday') {
            return redirect()->back()->with('error', 'Hari Libur Tidak bisa Check In');
        }

        foreach ($users as $user) {
            $absen = Present::whereUserId($user->id)->whereTanggal(date('Y-m-d'))->first();
            if (!$absen) {
                $alpha = true;
            }
        }

        if ($alpha) {
            foreach ($users as $user) {
                $hadir = Present::whereUserId($user->id)->whereTanggal(date('Y-m-d'))->first();
                if (!$hadir) {
                    if ($user->id != $request->user_id) {
                        Present::create([
                            'keterangan'    => 'Alpha',
                            'tanggal'       => date('Y-m-d'),
                            'user_id'       => $user->id
                        ]);
                    }
                }
            }
        }



        $position = strpos($request->image, ';');
        $sub = substr($request->image, 0, $position);
        $ext = explode('/', $sub)[1];

        $name = time() . "." . $ext;
        $img = Image::make($request->image)->resize(500, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->stream();
        $upload_path =  'foto-datang/';
        $image_url = $upload_path . $name;
        Storage::disk('sftp')->put($image_url, $img);

        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();

        $present = Present::whereUserId($request->user_id)->whereTanggal(date('Y-m-d'))->first();
        $data['denda'] = 0;
        if ($present) {
            if ($present->keterangan == 'Alpha') {
                $data['jam_masuk']          = date('H:i:s');
                $data['tanggal']            = date('Y-m-d');
                $data['user_id']            = $request->user_id;
                $data['foto_datang']        = $image_url;
                $data['lokasi_datang']      = $request->lokasi;
                $data['status_permohonan']  = 0;
                if (strtotime($data['jam_masuk']) >= strtotime($jamkerja->mulai_absen) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->mulai_kerja)) {
                    $data['keterangan'] = 'Masuk';
                } elseif (strtotime($data['jam_masuk']) > strtotime($jamkerja->mulai_kerja) && strtotime($data['jam_masuk']) < strtotime($jamkerja->mulai_sanksi)) {
                    $data['keterangan'] = 'Telat';
                } elseif (strtotime($data['jam_masuk']) >= strtotime($jamkerja->mulai_sanksi) && strtotime($data['jam_masuk']) < strtotime($jamkerja->maks_absen)) {
                    $data['keterangan'] = 'Telat';
                    $data['denda'] = total_sanksi($request->user_id, $data['jam_masuk'], $data['tanggal']);
                } else {
                    $data['keterangan'] = 'Alpha';
                }
                $present->update($data);
                CurrentLocation::updateOrCreate(["user_id" => $request->user_id, "date" => date('Y-m-d')], [

                    "lat" => explode(', ', $request->lokasi)[0],
                    "long" => explode(', ', $request->lokasi)[1],
                    "device" => 'web',

                ]);
                if (auth()->user()->role->role == 'Admin') {
                    return redirect()->route('kehadiran.index');
                } elseif (in_array(auth()->user()->role->role, ["Eselon 4", "Eselon 3", "Eselon 2", "Eselon 1",])) {
                    return redirect()->route('atasanPresents.index');
                } else {
                    return redirect()->route('daftar-hadir');
                }
                // return redirect()->back()->with('success','Check-in berhasil');
            } else {
                return redirect()->back()->with('error', 'Check-in gagal');
            }
        }

        $data['jam_masuk']          = date('H:i:s');
        $data['tanggal']            = date('Y-m-d');
        $data['user_id']            = $request->user_id;
        $data['foto_datang']        = $image_url;
        $data['lokasi_datang']      = $request->lokasi;
        $data['status_permohonan']  = 0;
        if (strtotime($data['jam_masuk']) >= strtotime($jamkerja->mulai_absen) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->mulai_kerja)) {
            $data['keterangan'] = 'Masuk';
        } elseif (strtotime($data['jam_masuk']) > strtotime($jamkerja->mulai_kerja) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->selesai_kerja)) {
            $data['keterangan'] = 'Telat';
        } elseif (strtotime($data['jam_masuk']) >= strtotime($jamkerja->mulai_sanksi) && strtotime($data['jam_masuk']) < strtotime($jamkerja->maks_absen)) {
            $data['keterangan'] = 'Telat';
            $data['denda'] = total_sanksi($request->user_id, $data['jam_masuk'], $data['tanggal']);
        } else {
            $data['keterangan'] = 'Alpha';
        }

        Present::create($data);
        CurrentLocation::updateOrCreate(["user_id" => $request->user_id, "date" => date('Y-m-d')], [

            "lat" => explode(', ', $request->lokasi)[0],
            "long" => explode(', ', $request->lokasi)[1],
            "device" => 'web',

        ]);
        if (auth()->user()->role->role == 'Admin') {
            return redirect()->route('kehadiran.index');
        } elseif (in_array(auth()->user()->role->role, ["Eselon 4", "Eselon 3", "Eselon 2", "Eselon 1",])) {
            return redirect()->route('atasanPresents.index');
        } else {
            return redirect()->route('daftar-hadir');
        }
    }

    public function checkOut(Request $request, Present $kehadiran)
    {
        $position = strpos($request->image, ';');
        $sub = substr($request->image, 0, $position);
        $ext = explode('/', $sub)[1];

        $name = time() . "." . $ext;
        $img = Image::make($request->image)->resize(500, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->stream();
        $upload_path =  'foto-pulang/';
        $image_url = $upload_path . $name;
        Storage::disk('sftp')->put($image_url, $img);

        $data['jam_keluar']     = date('H:i:s');
        $data['foto_pulang']    = $image_url;
        $data['lokasi_pulang']  = $request->lokasi;
        $jamkerja = JamKerja::whereN(date('N', strtotime($kehadiran->tanggal)))->where('shift_id', $kehadiran->user->shift_id)->first();
        if (strtotime($data['jam_keluar']) > strtotime($jamkerja->selesai_kerja)) {
            $data['total_jam'] = jam_total($kehadiran->jam_masuk, $jamkerja->selesai_kerja);
            $data['total_lembur'] = jam_total($jamkerja->selesai_kerja, $data['jam_keluar']);
        } else {
            $data['total_jam'] = jam_total($kehadiran->jam_masuk, $data['jam_keluar']);
            $data['total_lembur'] = null;
        }

        $kehadiran->update($data);
        if (auth()->user()->role->role == 'Admin') {
            return redirect()->route('kehadiran.index');
        } elseif (in_array(auth()->user()->role->role, ["Eselon 4", "Eselon 3", "Eselon 2", "Eselon 1",])) {
            return redirect()->route('atasanPresents.index');
        } else {
            return redirect()->route('daftar-hadir');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $present = Present::whereUserId($request->user_id)->whereTanggal(date('Y-m-d'))->first();
        $jamkerja = JamKerja::whereN(date('N'))->where('shift_id', auth()->user()->shift_id)->first();
        if ($present) {
            return redirect()->back()->with('error', 'Absensi hari ini telah terisi');
        }
        $data = $request->validate([
            'keterangan'    => ['required'],
            'user_id'    => ['required']
        ]);
        $data['tanggal'] = date('Y-m-d');
        if ($request->keterangan == 'Masuk' || $request->keterangan == 'Telat') {
            $data['jam_masuk'] = $request->jam_masuk;
            if (strtotime($data['jam_masuk']) >= strtotime($jamkerja->mulai_absen) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->mulai_kerja)) {
                $data['keterangan'] = 'Masuk';
            } elseif (strtotime($data['jam_masuk']) > strtotime($jamkerja->mulai_kerja) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->selesai_kerja)) {
                $data['keterangan'] = 'Telat';
            } else {
                $data['keterangan'] = 'Alpha';
            }
        }
        Present::create($data);
        return redirect()->back()->with('success', 'Kehadiran berhasil ditambahkan');
    }

    public function ubah(Request $request)
    {
        $present = Present::findOrFail($request->id);
        echo json_encode($present);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function permohonan($id)
    {
        $presents = Present::find($id);
        return view('presents.permohonan', compact('presents'));
    }

    public function simpanPermohonan(Request $request)
    {
        $present = Present::whereUserId($request->user_id)->whereTanggal($request->tgl)->first();
        $data['keterangan']         = $request->keterangan;
        $data['status_permohonan']  = 0;

        if ($request->file('foto')) {
            $data['foto_permohonan'] = $request->file('foto')->store('foto-permohonan');
        }
        $present->update($data);
        return redirect()->back()->with('success', 'Permohonan berhasil diajukan');
        // return redirect('/daftar-hadir')->with('success', 'Permohonan berhasil diajukan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $presents   = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'));

        $presents = $presents->orderBy('tanggal', 'desc')->paginate(10);

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
        return view('presents.show', compact('presents', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'jamkerja', 'my', 'tgl', 'hari'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Present  $kehadiran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Present $kehadiran)
    {
        $data = $request->validate([
            'keterangan'    => ['required']
        ]);

        $jamkerja = JamKerja::whereN(date_format(date_create($kehadiran->tanggal), 'N'))->where('shift_id', $kehadiran->user->shift_id)->first();

        if ($request->jam_keluar) {
            $data['jam_keluar'] = $request->jam_keluar;
            $data['total_jam']  = jam_total($kehadiran->jam_masuk, date('H:i:s'));
        }

        // if ($request->keterangan == 'Masuk' || $request->keterangan == 'Telat') {
        //     $data['jam_masuk'] = $request->jam_masuk;
        //     if (strtotime($data['jam_masuk']) >= strtotime($jamkerja->mulai_absen) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->mulai_kerja)) {
        //         $data['keterangan'] = 'Masuk';
        //     } elseif (strtotime($data['jam_masuk']) > strtotime($jamkerja->mulai_kerja) && strtotime($data['jam_masuk']) <= strtotime($jamkerja->selesai_kerja)) {
        //         $data['keterangan'] = 'Telat';
        //     } else {
        //         $data['keterangan'] = 'Alpha';
        //     }
        // } else {
        //     $data['jam_masuk'] = null;
        //     $data['jam_keluar'] = null;
        // }

        $data['jam_masuk'] = $request->jam_masuk;
        $data['jam_keluar'] = $request->jam_keluar;
        $data['keterangan'] = $request->keterangan;
        $kehadiran->update($data);
        return redirect()->back()->with('success', 'Kehadiran tanggal "' . date('d F Y', strtotime($kehadiran->tanggal)) . '" berhasil diubah');
    }

    public function kehadiran_pdf(Request $request)
    {
        // $kehadiran  = Present::all();
        $kehadiran  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('tanggal', 'asc')->get();
        // dd($kehadiran);
        $pdf = PDF::loadview('presents/kehadiran_pdf', ['kehadiran' => $kehadiran]);
        return $pdf->stream();
    }
    public function cetak_pdf(Request $request)
    {
        $us = $this->config()['user']->pluck('id')->toArray();
        $sel_uker = $request->uker ?? '';
        $sel_keterangan = $request->keterangan ?? '';
        $tanggal = $request->tanggal ?? '';
        $filter['uker'] = $sel_uker ? Unitkerja::whereid($sel_uker)->first()->unit_kerja : 'Semua';
        $filter['keterangan'] =  $request->keterangan ? $request->keterangan : 'Semua';
        $filter['tanggal'] = $tanggal ? $tanggal : date('Y-m');
        $kehadiran = Present::join('users', 'users.id', '=', 'presents.user_id');
        if ($sel_uker != '') {
            $kehadiran = $kehadiran->where('users.uker_id', $sel_uker);
        }
        if ($sel_keterangan != '') {
            $kehadiran = $kehadiran->where('presents.keterangan', $sel_keterangan);
        }
        if ($request->tanggal != '') {
            $kehadiran = $kehadiran->where('presents.tanggal', $request->tanggal);
        } else {
            $kehadiran = $kehadiran->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'));
        }
        $kehadiran = $kehadiran->whereIn('user_id', $us)->orderBy('tanggal', 'desc')->get();
        $ukers = Unitkerja::all();
        $keterangan = $this->keterangan;
        // $kehadiran  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('tanggal', 'desc')->get();

        $pdf = PDF::loadview('presents/presents_pdf', ['kehadiran' => $kehadiran, 'ukers' => $ukers, 'keterangan' => $keterangan, 'sel_uker' => $sel_uker, 'sel_keterangan' => $sel_keterangan, 'filter' => $filter]);
        return $pdf->stream();
        // return $pdf->download('laporan-aktifitas-pdf');
    }

    public function excelUser(Request $request, User $user)
    {
        $presents = Present::whereUserId($user->id)->where('tanggal', '>=', $request->periode_mulai)
            ->where('tanggal', '<=', $request->periode_selesai)
            ->whereNotIn('keterangan', ['Libur', 'Alpha'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $atasan = User::find($user->atasan_id);

        foreach ($presents as $i) {
            $status_permohonan = $i->status_permohonan;

            if ($status_permohonan == null) {
                return redirect()->back()->with('error', 'Terdapat data yang belum ditinjau, Silahkan hubungi ' . $atasan->nama . ' untuk meninjau data absen');
            }
        }

        $data = new PresentExport($user->id, $request->periode_mulai, $request->periode_selesai);

        return Excel::download($data, 'kehadiran-' . $user->username . '-' . $request->periode_mulai . '-' . $request->periode_selesai . '.xlsx');
    }

    public function excelUsers(Request $request)
    {
        return Excel::download(new UsersPresentExport($request->tanggal), 'kehadiran-' . $request->tanggal . '.xlsx');
    }
    public function reportDetail(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        return Excel::download(new PresentExport($user->id, $request->periode_mulai, $request->periode_selesai), 'kehadiran-' . $user->username . '-' . $request->periode_mulai . '-' . $request->periode_selesai . '.xlsx');
    }

    public function destroy($id)
    {
        try {
            Present::whereid($id)->delete();
            return response()->json(["message" => "Berhasil Hapus Absen"]);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Gagal Hapus Absen"], 400);
        }
    }
}
