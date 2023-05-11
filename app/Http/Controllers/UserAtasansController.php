<?php

namespace App\Http\Controllers;

use App\PersonalInformation;
use App\User;
use App\Present;
use App\Role;
use App\Unitkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserAtasansController extends Controller
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
            }elseif(auth()->user()->role_id == 1){
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id');
            }
            else{
                $lst = PersonalInformation::join('users','users.id','personal_information.user_id')
                ->where('personal_information.opd_id',auth()->user()->personalInformation->opd_id);
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
        $lst = $this->list();
        $users  = User::whereIn('id', $lst)->paginate(10);
        $total  = $users->total();
        $rank   = $users->firstItem();
        return view('atasan.index', compact('users','rank','total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('role_id','=','2')->get();
        $ukers = DB::table('users')
            ->join('unitkerjas','unitkerjas.id','=','users.uker_id')
            ->join('roles','roles.id','=','users.role_id')
            ->select('users.*','unitkerjas.unit_kerja','roles.role')
            ->get();
        // $ukers = Unitkerja::all();
        $roles = Role::all();
        return view('atasan.create', compact('users','ukers','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->validate([
            'nama'  => ['required', 'max:32', 'string'],
            // 'nrp'   => ['required', 'digits:9','unique:users'],
            'username'   => ['required', 'unique:users'],
            'role'  => ['required', 'numeric'],
            'uker'  => ['required', 'numeric'],
            'foto'  => ['image', 'mimes:jpeg,png,gif', 'max:2048']
        ]);
        $password = Str::random(10);
        $user['role_id']    = $request->role;
        if($request->atasan==""){
            $user['atasan_id']  = 0;
        } else {
            $user['atasan_id']  = $request->atasan;
        }
        $user['uker_id']    = $request->uker; // uker = unit kerja
        $user['password']   = Hash::make($password);
        if ($request->file('foto')) {
            $user['foto'] = $request->file('foto')->store('foto-profil');
        } else {
            $user['foto'] = 'default.jpg';
        }

        User::create($user);
        return redirect('/users')->with('success', 'User berhasil ditambahkan, password = '.$password);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $presents = Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('tanggal', 'desc')->paginate(5);
        $masuk = Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('masuk')->count();
        $telat = Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('telat')->count();
        $cuti = Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('cuti')->count();
        $alpha = Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('alpha')->count();
        $kehadiran = Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('telat')->get();
        $totalJamTelat = 0;
        foreach ($kehadiran as $present) {
            $totalJamTelat = $totalJamTelat + (\Carbon\Carbon::parse($present->jam_masuk)->diffInHours(\Carbon\Carbon::parse('07:30:00')));
        }
        $sanksi =  Present::whereUserId($user->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->sum('denda');

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
        return view('atasan.show', compact('user', 'presents', 'libur', 'masuk', 'telat', 'cuti', 'alpha', 'totalJamTelat', 'sanksi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $users = User::all();
        $ukers = Unitkerja::all();
        $roles = Role::all();
        return view('atasan.edit',compact('user','users','roles','ukers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user   = User::find($id);
        // $data['nrp'] = $request->username;
        $data['username'] = $request->username;
        $data['nama'] = $request->nama;
        $user->update($data);
        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $nama = $user->nama;
        if ($user->foto != 'default.jpg') {
            File::delete(public_path('storage'.'/'.$user->foto));
        }
        User::destroy($user->id);
        return redirect('/users')->with('success','User "'.$user->nama.'" berhasil dihapus');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gantiPassword()
    {
        return view('users.ganti-password');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password'                => 'required|min:6',
            'password_baru'           => 'required|min:6|required_with:konfirmasi_password|same:konfirmasi_password',
            'konfirmasi_password'     => 'required|min:6'
        ]);

        if (Hash::check($request->password, $user->password)) {
            if ($request->password == $request->konfirmasi_password) {
                return redirect()->back()->with('error','Password gagal diperbarui, tidak ada yang berubah pada kata sandi');
            } else {
                $user->password = Hash::make($request->konfirmasi_password);
                $user->save();
                return redirect()->back()->with('success','Password berhasil diperbarui');
            }
        } else {
            return redirect()->back()->with('error','Password tidak cocok dengan kata sandi lama');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profil(User $user)
    {
        $users = User::where('role_id','=','2')->get();
        $ukers = Unitkerja::all();
        return view('users.profil',compact('user','users','ukers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfil(Request $request, User $user)
    {
        $data = $request->validate([
            'nama'  => ['required', 'max:32', 'string'],
            'role'  => ['required', 'numeric'],
            'uker'  => ['required', 'numeric'],
            'foto'  => ['image', 'mimes:jpeg,png,gif', 'max:2048']
        ]);
        $data['nama']       = $request->nama;
        $data['role_id']    = $request->role;
        $data['uker_id']    = $request->uker;
        if ($request->file('foto')) {
            if ($user->foto != 'default.jpg') {
                File::delete(public_path('storage'.'/'.$user->foto));
            }
            $data['foto'] = $request->file('foto')->store('foto-profil');
        }
        $user->update($data);
        // $user->save();
        return redirect()->back()->with('success','Profil berhasil di perbarui');
    }

    public function search(Request $request)
    {
        $request->validate([
            'cari' => ['required']
        ]);
        $lst = $this->list($request->cari);
        
        $users = User::whereIn('id',$lst)
        ->paginate(10);
        $total  = $users->total();
        $rank = $users->firstItem();

        return view('atasan.index', compact('users','rank','total'));
    }

    public function password(Request $request, User $user)
    {
        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();

        return redirect()->back()->with('success','Password berhasil direset, Password = '.$password);
    }
}
