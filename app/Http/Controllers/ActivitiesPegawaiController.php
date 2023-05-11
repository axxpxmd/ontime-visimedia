<?php

namespace App\Http\Controllers;

use PDF;
use Image;
use App\User;
use App\Activitie;
use App\PersonalInformation;
use Illuminate\Http\Request;
use App\Exports\ActivitieExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersActivitieExport;
use Illuminate\Support\Facades\Storage;

class ActivitiesPegawaiController extends Controller
{
    public function list($cari = null){
        if(auth()->user()->role_id == 2){
            $lst = PersonalInformation::join('users','users.id','personal_information.user_id')
                ->where('personal_information.subunit_kerja_id',auth()->user()->personalInformation->subunit_kerja_id)
                ->where('users.role_id',3);
            }else if(auth()->user()->role_id == 4){
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id')
                ->where('personal_information.unit_kerja_id',auth()->user()->personalInformation->unit_kerja_id)
                ->whereIn('users.role_id',[3,2]);
            }
            else if(auth()->user()->role_id == 5){
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id')
                ->where('personal_information.opd_id',auth()->user()->personalInformation->opd_id)
                ->whereIn('users.role_id',[4,3,2]);
            }elseif(auth()->user()->role_id == 6){
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id')
                ->where('personal_information.opd_id',auth()->user()->personalInformation->opd_id);
            }elseif(auth()->user()->role_id == 1){
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id');
            }
            else{
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id')
                ->where('personal_information.user_id',auth()->user()->personalInformation->user_id);
            }

            if($cari != null){
                $lst->where('personal_information.nama','like','%'.$cari.'%');
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
        $lst = 
        $user = $this->list();
        $user[] = Auth::user()->id;
        $activities = Activitie::whereIn('user_id', $user)->where('tanggal', 'like', date('Y-m-%'))->with(['user:id,nama'])->orderBy('id', 'desc')->paginate(10);

        $rank = $activities->firstItem();
        return view('activities.index', compact('activities', 'rank', ));
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activitie = Activitie::find($id);
        if ($activitie->user_id != Auth::user()->id) {
            return redirect()->route('activities.index');
        }
        return view('activities.edit', compact('activitie'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'tanggal' => ['required']
        ]);
        $user = User::whereatasan_id(Auth::user()->id)->pluck('id')->toArray();
        $user[] = Auth::user()->id;
        $activities = Activitie::whereIn('user_id', $user)->where('tanggal', 'like', $request->tanggal)->with(['user:id,nama'])->orderBy('id', 'desc')->paginate(10);
        $rank = $activities->firstItem();
        return view('activities.index', compact('activities', 'rank'));
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
        return view('activities.show', compact('Activities', 'masuk', 'telat', 'cuti', 'alpha'));
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
            $name = time().".".$request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-activities/';
            $image_url = $upload_path.$name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $data['foto'] = $image_url;
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
    public function show($id)
    {
        $activitie = Activitie::find($id);
        return view('activities.show', compact('activitie', ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activitie  $kehadiran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $activitie = Activitie::find($id);
        $request->validate([
            'aktifitas'     => ['required'],
            'tgl_aktifitas' => ['required'],
            'jam_mulai'     => ['required'],
            'jam_selesai'   => ['required'],
            'keterangan'    => ['required']
        ]);

        if ($request->file('foto')) {
            if ($activitie->foto != 'default.jpg') {
                if (Storage::disk('sftp')->exists($activitie->foto)) {
                    Storage::disk('sftp')->delete($activitie->foto);
                }
            }


            $name = time().".".$request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-activities/';
            $image_url = $upload_path.$name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $foto = $image_url;
        }

        $activitie->update([
            'aktifitas'     => $request->aktifitas,
            'tanggal'       => $request->tgl_aktifitas,
            'jam_mulai'     => $request->jam_mulai,
            'jam_selesai'   => $request->jam_selesai,
            'keterangan'    => $request->keterangan,
            'foto'          => $foto ?? $activitie->foto
         ]);

        return redirect()->back()->with('success', 'Aktifitas berhasil diperbarui');
    }
    public function update2($id)
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

    public function acc($id)
    {
        $auth = Auth::user()->id;
        $activities = Activitie::findOrFail($id);

        // if ($activities->user->atasan_id != $auth) {
        //     return redirect()->back();
        // }

        return view('activities.acc', compact('activities'));
    }

    public function cetak_pdf()
    {
        $user = User::whereatasan_id(Auth::user()->id)->pluck('id')->toArray();
        $user[] = Auth::user()->id;
        $activities = Activitie::whereIn('user_id', $user)->where('tanggal', 'like', date('Y-m-%'))->with(['user:id,nama'])->orderby('user_id', 'asc')->orderby('id', 'asc')->get();

        $pdf = PDF::loadview('activities/aktifitas_pdf', ['aktifitas'=>$activities]);
        return $pdf->stream();
        // return $pdf->download('laporan-aktifitas-pdf');
    }
    public function cetak_pdf2()
    {
        $user[] = Auth::user()->id;
        $activities = Activitie::whereIn('user_id', $user)->where('tanggal', 'like', date('Y-m-%'))->with(['user:id,nama'])->orderby('id', 'desc')->get();

        $pdf = PDF::loadview('activities/aktifitas_pdf', ['aktifitas'=>$activities]);
        return $pdf->stream();
        // return $pdf->download('laporan-aktifitas-pdf');
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
