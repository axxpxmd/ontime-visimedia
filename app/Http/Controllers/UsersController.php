<?php

namespace App\Http\Controllers;

use Image;
use App\Opd;
use App\Role;
use App\User;
use DateTime;
use App\Shift;
use App\Present;
use App\Location;
use App\Unitkerja;
use App\Trlocation;
use App\UnitKerjaa;
use App\JenisJabatan;
use App\RiwayatKerja;
use App\SubUnitKerja;
use App\CurrentLocation;
use App\Helpers\Firebase;
use App\RiwayatPelatihan;

use App\RiwayatPendidikan;
use Illuminate\Support\Str;
use App\PersonalInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Imports\PresensiImport;
use App\Tmsanksi;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use ParagonIE\Sodium\Core\Curve25519\Ge\P2;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller
{
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

    public function access($user)
    {
        $auth = auth()->user();
        if ($auth->role_id ==  7) {
            if ($user->personalInformation->opd_id != $auth->personalInformation->opd_id) {
                return false;
            }
        }
        return true;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total  = $this->config()['user']->get();
        $users  = $this->config()['user']->paginate(10);
        $rank   = $users->firstItem();
        $opds = $this->config()['opds'];

        return view('users.index', compact('users', 'rank', 'total', 'opds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles      = $this->config()['roles'];
        $opds = $this->config()['opds'];
        $shifts = Shift::all();
        $lokasi   = Location::all();
        $tmsanksi = Tmsanksi::all();
        return view('users.create', compact('roles', 'lokasi', 'shifts', 'opds', 'tmsanksi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->role);
        $validate = [
            'nama'  => ['required', 'max:32', 'string'],
            'username'   => ['required', 'unique:users'],


            'shift_id'  => ['required', 'numeric'],
            'role' =>  ['required', 'numeric'],
            'foto'  => ['image', 'mimes:jpeg,png,gif', 'max:2048']
        ];
        if (!in_array($request->role, [1, 6])) {
            $validate['opd_id'] = ['required'];
        }

        $user = $request->validate($validate);
        $password = 123456789;
        $user['role_id']    = $request->role;
        $user['shift_id'] = $request->shift_id;
        $data['s_akun'] = 1;
        // uker = unit kerja
        $user['lokasi_id']  = $request->lokasi;
        $user['password']   = Hash::make($password);
        $user['sallary'] = $request->gaji;
        $user['tmsanksi_id'] = $request->tmsanksi_id;

        if ($request->subunit_kerja_id) {
            $unitkerja = Unitkerja::updateOrCreate(['unit_kerja' => SubUnitKerja::whereid($request->subunit_kerja_id)->first()->nama]);
        } elseif ($request->unit_kerja_id) {
            $unitkerja = Unitkerja::updateOrCreate(['unit_kerja' => UnitKerjaa::whereid($request->unit_kerja_id)->first()->nama]);
        } elseif ($request->opd_id) {
            $unitkerja = Unitkerja::updateOrCreate(['unit_kerja' => Opd::whereid($request->opd_id)->first()->nama]);
        }
        $user['uker_id'] = isset($unitkerja->unit_kerja) ? $unitkerja->unit_kerja : null;
        if ($request->file('foto')) {
            $name = time() . "." . $request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-profil/';
            $image_url = $upload_path . $name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $user['foto'] = $image_url;
        } else {
            $user['foto'] = 'default.jpg';
        }


        $user = User::create($user);

        PersonalInformation::create([
            'nama' => $request->nama,
            'user_id' => $user->id,

            'opd_id' => $request->opd_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'subunit_kerja_id' => $request->subunit_kerja_id,
            'nik' => $request->nik,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'npwp' => $request->npwp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_kawin' => $request->status_kawin,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'golongan_darah' => $request->golongan_darah,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_domisili' => $request->alamat_domisili,

            'gaji' => $request->gaji

        ]);


        return redirect('/users')->with('success', 'User berhasil ditambahkan, password = ' . $password);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!$this->access($user)) {
            return redirect()->route('users.index');
        }
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


        $libur = false;
        $holiday = null;
        $jenisJabatan = JenisJabatan::all();
        $tingkat_pendidikan = ['SD', 'SMP', 'SMA', 'SMK', 'S1', 'S2', 'S3'];
        return view('users.show', compact('user', 'presents', 'libur', 'masuk', 'telat', 'cuti', 'alpha', 'totalJamTelat', 'sanksi', 'jenisJabatan', 'tingkat_pendidikan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (!$this->access($user)) {
            return redirect()->route('users.index');
        }

        $ukers = Unitkerja::all();
        $roles      = $this->config()['roles'];
        $opds = $this->config()['opds'];
        $shifts = Shift::all();
        $tmsanksi = Tmsanksi::all();
        $lokasi = Location::all();
        $total_lokasi   = Location::count();
        $selected = DB::table('users')
            ->select('lokasi_id')
            ->get();
        return view('users.edit', compact('user', 'ukers', 'roles', 'lokasi', 'total_lokasi', 'selected', 'shifts', 'opds', 'tmsanksi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validate = [
            'nama'  => ['required', 'max:32', 'string'],

            'username'   => ['required', Rule::unique('users', 'username')->ignore($user)],
            'role'  => ['required', 'numeric'],
            'shift_id'  => ['required', 'numeric'],


            'foto'  => ['image', 'mimes:jpeg,png', 'max:2048'],

        ];
        if (!in_array($request->role, [1, 6])) {
            $validate['opd_id'] = ['required'];
        }
        $data = $request->validate($validate);
        $data['role_id'] = $request->role;
        $data['shift_id'] = $request->shift_id;
        $data['sallary'] = $request->gaji;
        $data['atasan_id'] = $request->atasan_id;
        $data['s_akun'] = $request->s_akun;

        $data['lokasi_id']  = $request->lokasi;
        $data['tmsanksi_id']  = $request->tmsanksi_id;

        if ($request->subunit_kerja_id) {
            $unitkerja = Unitkerja::updateOrCreate(['unit_kerja' => SubUnitKerja::whereid($request->subunit_kerja_id)->first()->nama]);
        } elseif ($request->unit_kerja_id) {
            $unitkerja = Unitkerja::updateOrCreate(['unit_kerja' => UnitKerjaa::whereid($request->unit_kerja_id)->first()->nama]);
        } elseif ($request->opd_id) {
            $unitkerja = Unitkerja::updateOrCreate(['unit_kerja' => Opd::whereid($request->opd_id)->first()->nama]);
        }
        $data['uker_id'] = isset($unitkerja->id) ? $unitkerja->id : null;
        //    dd($unitkerja);
        if ($request->file('foto')) {
            if ($user->foto != 'default.jpg') {
                if (Storage::disk('sftp')->exists($user->foto)) {
                    Storage::disk('sftp')->delete($user->foto);
                }
            }


            $name = time() . "." . $request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-profil/';
            $image_url = $upload_path . $name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $data['foto'] = $image_url;
        }
        // if ($request->file('sk')) {
        //     if (Storage::disk('sftp')->exists($user->sk)) {
        //         Storage::disk('sftp')->delete($user->sk);
        //     }



        //     $name = time().".".$request->file('sk')->getClientOriginalExtension();

        //     $upload_path =  'sk/';
        //     $sk_url = $upload_path.$name;
        //     // $img->save($sk_url);
        //     Storage::disk('sftp')->put($sk_url, file_get_contents($request->file('sk')));

        //     $data['sk'] = $sk_url;
        // }
        // $data_lokasi['user_id']  = $request->user_id;
        // $data_lokasi['lokasi_id']  = $request->lokasi;
        $user->update($data);

        //Personal Information

        $user->personalInformation->update([
            'nama' => $request->nama,

            'opd_id' => $request->opd_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'subunit_kerja_id' => $request->subunit_kerja_id,
            'nik' => $request->nik,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'npwp' => $request->npwp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_kawin' => $request->status_kawin,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'golongan_darah' => $request->golongan_darah,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_domisili' => $request->alamat_domisili,

            'gaji' => $request->gaji

        ]);
        // $lokasi->update($data_lokasi);
        return redirect()->back()->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!$this->access($user)) {
            return redirect()->route('users.index');
        }
        $nama = $user->nama;
        if ($user->foto != 'default.jpg') {
            File::delete(public_path('storage' . '/' . $user->foto));
        }
        User::destroy($user->id);
        return redirect('/users')->with('success', 'User "' . $user->nama . '" berhasil dihapus');
    }

    public function getAtasan(Request $request)
    {
        $role = $request->role;
        $opd_id = $request->opd_id;
        $user = User::select('users.id', 'users.role_id', 'users.nama')
            ->join('personal_information', 'users.id', 'personal_information.user_id')
            ->where('personal_information.opd_id', $opd_id)
            ->whereIn('role_id', [2,4,5,6]);

        // if ($role == 2) {
        //     $user =  $user->whererole_id(4);
        // }
        // if ($role == 3) {
        //     $user = $user->where('users.role_id', 2);
        // }
        // if ($role == 4) {
        //     $user =  $user->whererole_id(5);
        // }
        // if ($role == 5) {
        //     $user =  $user->whererole_id(6);
        // }

        $user  = $user->get();
        return response()->json($user);
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
                return redirect()->back()->with('error', 'Password gagal diperbarui, tidak ada yang berubah pada kata sandi');
            } else {
                $user->password = Hash::make($request->konfirmasi_password);
                $user->save();
                return redirect()->back()->with('success', 'Password berhasil diperbarui');
            }
        } else {
            return redirect()->back()->with('error', 'Password tidak cocok dengan kata sandi lama');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profil_backup(User $user)
    {
        // dd($user);
        $users = User::where('id', '=', auth()->user()->atasan_id)->get();
        $ukers = Unitkerja::all();
        $shifts = Shift::all();
        if (auth()->user()->role->role == 'Admin') {
            $masuk  = Present::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('masuk')->count();
            $telat  = Present::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('telat')->count();
            $izin   = Present::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('izin')->count();
            $sakit  = Present::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('sakit')->count();
            $cuti   = Present::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('cuti')->count();
            $alpha  = Present::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('alpha')->count();
        } else {
            $masuk  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('masuk')->count();
            $telat  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('telat')->count();
            $izin   = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('izin')->count();
            $sakit  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('sakit')->count();
            $cuti   = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('cuti')->count();
            $alpha  = Present::whereUserId(auth()->user()->id)->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->whereKeterangan('alpha')->count();
        }
        return view('users.profil', compact('user', 'users', 'ukers', 'masuk', 'telat', 'izin', 'sakit', 'cuti', 'alpha', 'shifts'));
    }

    public function profil()
    {
        $user = auth()->user();
        $roles = Role::all();
        $shifts = Shift::all();
        $lokasi = Location::all();
        $opds = Opd::all();
        $jenisJabatan = JenisJabatan::all();
        $tingkat_pendidikan = ['SD', 'SMP', 'SMA', 'DIII', 'SMK', 'S1', 'S2', 'S3'];
        return view('users.profil_v2', compact('user', 'roles', 'shifts', 'lokasi', 'opds', 'jenisJabatan', 'tingkat_pendidikan'));
    }

    public function riwayat_kerja(Request $request)
    {
        $riwayat_kerja = PersonalInformation::whereid($request->personal_information_id)->first()->riwayat_kerja;


        return DataTables::of($riwayat_kerja->sortBy('created_at'))

            ->addColumn('action', function ($p) {
                $html = "";
                if (in_array($p->status, [0, 2])) {
                    $html .=  "
                        <a onclick='editRiwayatKerja(" . $p->id . ",1)'  href='javascript:;' title='Edit Riwayat Kerja' class='btn btn-sm btn-primary mr-0'><i class='fas fa-pencil-alt '></i></a>
                        <a href='#' onclick='removeRiwayat(" . $p->id . ",1)' class='btn btn-sm btn-danger mr-1 title='Hapus Riwayat Kerja'><i class='fas fa-trash '></i></a>";
                } elseif (auth()->user()->role_id == 1) {
                    $html .=  "
                        <a onclick='editRiwayatKerja(" . $p->id . ",1)'  href='javascript:;' title='Edit Riwayat Kerja' class='btn btn-sm btn-primary mr-0'><i class='fas fa-pencil-alt '></i></a>
                        ";
                }

                if ($p->status == 0) {
                    $html .= '<button class="btn btn-sm btn-warning" title="Belum diverifikasi"><i class="fas fa-exclamation-circle"></i></button>';
                } elseif ($p->status == 1) {
                    $html .= '<button class="btn btn-sm btn-success" title="Sudah diverifikasi"><i class="fas fa-check"></i></button>';
                } else {
                    $html .= '<button class="btn btn-sm btn-danger" title="Verifikasi ditolak"><i class="fas fa-times"></i></button>';
                }



                return $html;
            })
            ->editColumn('dokumen', function ($p) {
                return "<a href='" . config('app.ftp_src') . $p->dokumen . "' target='_blank'  class='btn btn-sm btn-secondary mr-1 title='File'><i class='fas fa-file '></i></a>";
            })
            ->editColumn('jabatan_id', function ($p) {
                return $p->jabatan->nama;
            })



            ->rawColumns(['action', 'dokumen'])
            ->toJson();
    }

    public function riwayat_kerja_edit($id)
    {
        return RiwayatKerja::find($id);
    }
    public function riwayat_kerja_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tahun' => 'required',
            'nomer_sk' => 'required',
            'tanggal_sk' => 'required',
            'pejabat_sk' => 'required',
            'tmt_jabatan' => 'required',
            'jabatan_id' => 'required',

            'dokumen' => 'required|mimes:pdf',
        ]);
        if ($request->file('dokumen')) {
            $name = time() . "." . $request->file('dokumen')->getClientOriginalExtension();

            $upload_path =  'riwayat-kerja/' . auth()->user()->id . '/';
            $url = $upload_path . $name;
            // $img->save($url);
            // dd($url);
            Storage::disk('sftp')->put($url, File::get($request->dokumen));
        }


        RiwayatKerja::create([
            'personal_information_id' => $request->personal_information_id,
            'tahun' => $request->tahun,
            'nomer_sk' => $request->nomer_sk,
            'tanggal_sk' => $request->tanggal_sk,
            'pejabat_sk' => $request->pejabat_sk,
            'tmt_jabatan' => $request->tmt_jabatan,
            'jabatan_id' => $request->jabatan_id,
            'status' => $request->statusRk ?? 0,
            'dokumen' => $url ?? null
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat kerja berhasil tersimpan.'
        ]);
    }
    public function riwayat_kerja_update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required',
            'nomer_sk' => 'required',
            'tanggal_sk' => 'required',
            'pejabat_sk' => 'required',
            'tmt_jabatan' => 'required',
            'jabatan_id' => 'required',

            // 'dokumen' => 'required',
        ]);
        $rk = RiwayatKerja::find($id);

        if ($request->file('dokumen')) {
            $request->validate([
                'dokumen' => 'required|mimes:pdf'
            ]);


            $name = time() . "." . $request->file('dokumen')->getClientOriginalExtension();

            $upload_path =  'riwayat-kerja/' . auth()->user()->id . '/';
            $url = $upload_path . $name;
            // $img->save($url);
            // dd($url);
            Storage::disk('sftp')->put($url, File::get($request->dokumen));

            if ($rk->dokumen && Storage::disk('sftp')->exists($rk->dokumen)) {
                Storage::disk('sftp')->delete($rk->dokumen);
            }
        }


        $rk->update([

            'tahun' => $request->tahun,
            'nomer_sk' => $request->nomer_sk,
            'tanggal_sk' => $request->tanggal_sk,
            'pejabat_sk' => $request->pejabat_sk,
            'tmt_jabatan' => $request->tmt_jabatan,
            'jabatan_id' => $request->jabatan_id,
            'status' => $request->statusRk ?? 0,
            'dokumen' => $url ?? $rk->dokumen
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat kerja berhasil diupdate.'
        ]);
    }


    public function riwayat_pelatihan(Request $request)
    {
        $riwayat_pelatihan = PersonalInformation::whereid($request->personal_information_id)->first()->riwayat_pelatihan;
        // dd($riwayat_pelatihan);

        return DataTables::of($riwayat_pelatihan->sortBy('created_at'))

            ->addColumn('action', function ($p) {
                $html = "";
                if (in_array($p->status, [0, 2])) {
                    $html .=  "
                        <a onclick='editRiwayatPelatihan(" . $p->id . ")'  href='javascript:;' title='Edit Riwayat Pelatihan' class='btn btn-sm btn-primary mr-0'><i class='fas fa-pencil-alt '></i></a>
                        <a href='#' onclick='removeRiwayat(" . $p->id . ",3)' class='btn btn-sm btn-danger mr-1 title='Hapus Riwayat Pelatihan'><i class='fas fa-trash '></i></a>";
                } elseif (auth()->user()->role_id == 1) {
                    $html .=  "
                    <a onclick='editRiwayatPelatihan(" . $p->id . ")'  href='javascript:;' title='Edit Riwayat Pelatihan' class='btn btn-sm btn-primary mr-0'><i class='fas fa-pencil-alt '></i></a>
                        ";
                }

                if ($p->status == 0) {
                    $html .= '<button class="btn btn-sm btn-warning" title="Belum diverifikasi"><i class="fas fa-exclamation-circle"></i></button>';
                } elseif ($p->status == 1) {
                    $html .= '<button class="btn btn-sm btn-success" title="Sudah diverifikasi"><i class="fas fa-check"></i></button>';
                } else {
                    $html .= '<button class="btn btn-sm btn-danger" title="Verifikasi ditolak"><i class="fas fa-times"></i></button>';
                }

                return $html;
            })
            ->editColumn('dokumen', function ($p) {
                return "<a href='" . config('app.ftp_src') . $p->dokumen . "' target='_blank'  class='btn btn-sm btn-secondary mr-1 title='File'><i class='fas fa-file '></i></a>";
            })



            ->rawColumns(['action', 'dokumen'])
            ->toJson();
    }

    public function riwayat_pelatihan_edit($id)
    {
        return RiwayatPelatihan::find($id);
    }
    public function riwayat_pelatihan_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_kegiatan' => 'required',
            'penyelenggara' => 'required',
            'nomor_sertifikat' => 'required',
            'tanggal_sertifikat' => 'required',
            'dokumen' => 'required|mimes:pdf',
        ]);
        if ($request->file('dokumen')) {
            $name = time() . "." . $request->file('dokumen')->getClientOriginalExtension();

            $upload_path =  'riwayat-pelatihan/' . auth()->user()->id . '/';
            $url = $upload_path . $name;
            // $img->save($url);
            // dd($url);
            Storage::disk('sftp')->put($url, File::get($request->dokumen));
        }


        RiwayatPelatihan::create([
            'personal_information_id' => $request->personal_information_id,
            'nama_kegiatan' => $request->nama_kegiatan,
            'penyelenggara' => $request->penyelenggara,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_sertifikat' => $request->tanggal_sertifikat,
            'status' => $request->statusRp ?? 0,
            'dokumen' => $url ?? null
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat pelatihan berhasil tersimpan.'
        ]);
    }
    public function riwayat_pelatihan_update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'penyelenggara' => 'required',
            'nomor_sertifikat' => 'required',
            'tanggal_sertifikat' => 'required',
        ]);
        $rk = RiwayatPelatihan::find($id);

        if ($request->file('dokumen')) {
            $request->validate([
                'dokumen' => 'required|mimes:pdf'
            ]);


            $name = time() . "." . $request->file('dokumen')->getClientOriginalExtension();

            $upload_path =  'riwayat-pelatihan/' . auth()->user()->id . '/';
            $url = $upload_path . $name;
            // $img->save($url);
            // dd($url);
            Storage::disk('sftp')->put($url, File::get($request->dokumen));

            if ($rk->dokumen && Storage::disk('sftp')->exists($rk->dokumen)) {
                Storage::disk('sftp')->delete($rk->dokumen);
            }
        }


        $rk->update([


            'nama_kegiatan' => $request->nama_kegiatan,
            'penyelenggara' => $request->penyelenggara,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_sertifikat' => $request->tanggal_sertifikat,
            'status' => $request->statusRp ?? 0,
            'dokumen' => $url ?? $rk->dokumen
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat pelatihan berhasil diupdate.'
        ]);
    }

    public function riwayat_pendidikan(Request $request)
    {
        $riwayat_pendidikan = PersonalInformation::whereid($request->personal_information_id)->first()->riwayat_pendidikan;
        // dd($riwayat_pendidikan);

        return DataTables::of($riwayat_pendidikan->sortBy('created_at'))

            ->addColumn('action', function ($p) {
                $html = "";
                if (in_array($p->status, [0, 2])) {
                    $html .=  "
                    <a onclick='editRiwayatPendidikan(" . $p->id . ")'  href='javascript:;' title='Edit Riwayat Pendidikan' class='btn btn-sm btn-primary mr-0'><i class='fas fa-pencil-alt '></i></a>
                    <a href='#' onclick='removeRiwayat(" . $p->id . ",2)' class='btn btn-sm btn-danger mr-1 title='Hapus Riwayat Pendidikan'><i class='fas fa-trash '></i></a>";
                } elseif (auth()->user()->role_id == 1) {
                    $html .=  "
                <a onclick='editRiwayatPendidikan(" . $p->id . ")'  href='javascript:;' title='Edit Riwayat Pendidikan' class='btn btn-sm btn-primary mr-0'><i class='fas fa-pencil-alt '></i></a>
                    ";
                }

                if ($p->status == 0) {
                    $html .= '<button class="btn btn-sm btn-warning" title="Belum diverifikasi"><i class="fas fa-exclamation-circle"></i></button>';
                } elseif ($p->status == 1) {
                    $html .= '<button class="btn btn-sm btn-success" title="Sudah diverifikasi"><i class="fas fa-check"></i></button>';
                } else {
                    $html .= '<button class="btn btn-sm btn-danger" title="Verifikasi ditolak"><i class="fas fa-times"></i></button>';
                }

                return $html;
            })
            ->editColumn('dokumen', function ($p) {
                return "<a href='" . config('app.ftp_src') . $p->dokumen . "' target='_blank'  class='btn btn-sm btn-secondary mr-1 title='File'><i class='fas fa-file '></i></a>";
            })



            ->rawColumns(['action', 'dokumen'])
            ->toJson();
    }

    public function riwayat_pendidikan_edit($id)
    {
        return RiwayatPendidikan::find($id);
    }
    public function riwayat_pendidikan_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tingkat' => 'required',
            'jurusan' => 'required',
            'lembaga' => 'required',
            'nomor_ijazah' => 'required',
            'tgl_ijazah' => 'required',
            'tahun_lulus' => 'required',
            'nilai' => 'required',
            'dokumen' => 'required|mimes:pdf',
        ]);
        if ($request->file('dokumen')) {
            $name = time() . "." . $request->file('dokumen')->getClientOriginalExtension();

            $upload_path =  'riwayat-pendidikan/' . auth()->user()->id . '/';
            $url = $upload_path . $name;
            // $img->save($url);
            // dd($url);
            Storage::disk('sftp')->put($url, File::get($request->dokumen));
        }


        RiwayatPendidikan::create([
            'personal_information_id' => $request->personal_information_id,
            'tingkat' => $request->tingkat,
            'jurusan' => $request->jurusan,
            'lembaga' => $request->lembaga,
            'nomor_ijazah' => $request->nomor_ijazah,
            'tgl_ijazah' => $request->tgl_ijazah,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai' => $request->nilai,
            'status' => $request->statusRpen ?? 0,
            'dokumen' => $url ?? null
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat pendidikan berhasil tersimpan.'
        ]);
    }
    public function riwayat_pendidikan_update(Request $request, $id)
    {
        $request->validate([
            'tingkat' => 'required',
            'jurusan' => 'required',
            'lembaga' => 'required',
            'nomor_ijazah' => 'required',
            'tgl_ijazah' => 'required',
            'tahun_lulus' => 'required',
            'nilai' => 'required',
        ]);
        $rk = RiwayatPendidikan::find($id);

        if ($request->file('dokumen')) {
            $request->validate([
                'dokumen' => 'required|mimes:pdf'
            ]);


            $name = time() . "." . $request->file('dokumen')->getClientOriginalExtension();

            $upload_path =  'riwayat-pendidikan/' . auth()->user()->id . '/';
            $url = $upload_path . $name;
            // $img->save($url);
            // dd($url);
            Storage::disk('sftp')->put($url, File::get($request->dokumen));

            if ($rk->dokumen && Storage::disk('sftp')->exists($rk->dokumen)) {
                Storage::disk('sftp')->delete($rk->dokumen);
            }
        }


        $rk->update([


            'tingkat' => $request->tingkat,
            'jurusan' => $request->jurusan,
            'lembaga' => $request->lembaga,
            'nomor_ijazah' => $request->nomor_ijazah,
            'tgl_ijazah' => $request->tgl_ijazah,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai' => $request->nilai,
            'status' => $request->statusRpen ?? 0,
            'dokumen' => $url ?? $rk->dokumen
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data riwayat pendidikan berhasil diupdate.'
        ]);
    }

    public function remove_riwayat($id, Request $request)
    {
        if ($request->type == 1) {
            $rk = RiwayatKerja::whereid($id)->first();
            $message  =  'Data riwayat kerja berhasil dihapus.';
            if ($rk->dokumen && Storage::disk('sftp')->exists($rk->dokumen)) {
                Storage::disk('sftp')->delete($rk->dokumen);
            }
            $rk->delete();
        } elseif ($request->type == 3) {
            $rp = RiwayatPelatihan::whereid($id)->first();
            $message  =  'Data riwayat pelatihan berhasil dihapus.';
            if ($rp->dokumen && Storage::disk('sftp')->exists($rp->dokumen)) {
                Storage::disk('sftp')->delete($rp->dokumen);
            }
            $rp->delete();
        } elseif ($request->type == 2) {
            $rp = RiwayatPendidikan::whereid($id)->first();
            $message  =  'Data riwayat pendidikan berhasil dihapus.';
            if ($rp->dokumen && Storage::disk('sftp')->exists($rp->dokumen)) {
                Storage::disk('sftp')->delete($rp->dokumen);
            }
            $rp->delete();
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
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
                if (Storage::disk('sftp')->exists($user->foto)) {
                    Storage::disk('sftp')->delete($user->foto);
                }
            }


            $name = time() . "." . $request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-profil/';
            $image_url = $upload_path . $name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $data['foto'] = $image_url;
        }
        $user->update($data);
        // $user->save();
        return redirect()->back()->with('success', 'Profil berhasil di perbarui');
    }

    public function updateAkun(Request $request)
    {
        $user = User::whereid(auth()->user()->id)->first();

        $data = $request->validate([

            'username'   => ['required', Rule::unique('users', 'username')->ignore($user)],
            'foto'  => ['image', 'mimes:jpeg,png,gif', 'max:2048']
        ]);
        // dd($user);

        if ($request->file('foto')) {
            if ($user->foto != 'default.jpg') {
                if (Storage::disk('sftp')->exists($user->foto)) {
                    Storage::disk('sftp')->delete($user->foto);
                }
            }


            $name = time() . "." . $request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-profil/';
            $image_url = $upload_path . $name;
            Storage::disk('sftp')->put($image_url, $img);

            $data['foto'] = $image_url;
        }
        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil di perbarui');
    }
    public function updatePersonal(Request $request)
    {
        $user = User::whereid(auth()->user()->id)->first();

        $user->personalInformation->update([
            'nama' => $request->nama,


            'nik' => $request->nik,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'npwp' => $request->npwp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_kawin' => $request->status_kawin,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'golongan_darah' => $request->golongan_darah,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_domisili' => $request->alamat_domisili,
            'opd_id' => $request->opd_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'subunit_kerja_id' => $request->subunit_kerja_id,

        ]);

        $user->update([
            'sallary' => $request->gaji,
            'nama' => $request->nama
        ]);

        return redirect()->back()->with('success', 'Profil berhasil di perbarui');
    }

    public function search(Request $request)
    {
        $config = $this->config();
        $ukers      = Unitkerja::all();
        // $request->validate([
        //     'cari' => ['required']
        // ]);
        $users = User::select('users.*', 'personal_information.opd_id', 'personal_information.unit_kerja_id', 'personal_information.subunit_kerja_id')
            ->join('personal_information', 'users.id', '=', 'personal_information.user_id');
        if ($request->cari != '') {
            $users->where('users.nama', 'like', '%' . $request->cari . '%');
        }

        if ($request->opd_id != '') {
            $users->where('personal_information.opd_id', $request->opd_id);
        }
        if ($request->unit_kerja_id != '') {
            $users->where('personal_information.unit_kerja_id', $request->unit_kerja_id);
        }
        if ($request->subunit_kerja_id != '') {
            $users->where('personal_information.subunit_kerja_id', $request->subunit_kerja_id);
        }

        if ($config['role_id'] != 1) {
            $users->where('personal_information.opd_id', auth()->user()->personalInformation->opd_id);
        }


        $users = $users->paginate(10);
        $rank = $users->firstItem();
        $total  = $users;
        $opds = $this->config()['opds'];

        // dd($total);
        return view('users.index', compact('users', 'rank', 'total', 'ukers', 'opds'));
    }

    public function password(Request $request, User $user)
    {
        $password = 123456789;
        $user->password = Hash::make($password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil direset, Password = ' . $password);
    }

    public function getUser()
    {
        return response()->json(User::all());
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
    public function maps()
    {
        return view('users.map');
    }
    public function get_location()
    {
        $token = User::whereNotNull('token_firebase')->pluck('token_firebase', 'nama')->toArray();

        // $dt = Firebase::getLocation($token);
        // $loc = CurrentLocation::select('current_location.*','users.nama','users.foto','unitkerjas.unit_kerja')->join('users','users.id','current_location.user_id')->join('unitkerjas','unitkerjas.id','users.uker_id')->get();
        $user = User::whereatasan_id(auth()->user()->id)->pluck('id')->toArray();
        if (count($user) > 0) {
            $loc = CurrentLocation::with(['user'])->whereIn('user_id', $user)->where('date', date('Y-m-d'))->get();
            foreach ($loc as $k => $i) {
                $loc[$k]->unitkerjas = getUnitKerja($i->user_id);
            }
        } else {
            $loc = CurrentLocation::with(['user'])->where('date', date('Y-m-d'))->get();
            foreach ($loc as $k => $i) {
                $loc[$k]->unitkerjas = getUnitKerja($i->user_id);
            }
            // dd($loc);
        }
        return response()->json($loc);
    }
    public function importAbsen($id)
    {
        $user = User::findOrFail($id);

        
       
        return view('users.importAbsen', compact('id', 'user'));
    }
    public function importAbsenStoreStep1(Request $request)
    {
        $request->validate([
            'fileExcel' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        $file = $request->file('fileExcel');
        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls', 'XLSX', 'XLS'])) {
            return response()->json([
                'message' => "Terdapat kesalahan saat menyimpan data.<br/>Error Allow Extension."
            ], 422);
        }



        $rows = Excel::toArray(new PresensiImport(), $request->file('fileExcel'));
        // dd($rows);
        $err = [];
        foreach ($rows[0] as $k => $r) {
            $no = $k + 1;
            if ($k != 0) {
                if (!in_array($r[1], ["Libur", 'Cuti', 'Sakit', 'Izin', 'Masuk', 'Telat'])) {
                    $err['keterangan' . $no] = ['Baris ' . $no . ' Format Keterangan Tidak Sesuai "Libur"/"Cuti"/"Sakit"/"Izin"/"Masuk" .'];
                }
                if ($this->validateDate($r[0]) == false) {
                    $err['tanggal' . $no] = ['Baris ' . $no . ' Format Tanggal Tidak Sesuai (d/m/Y).'];
                }

                if (!in_array($r[1], ["Libur", 'Cuti', 'Sakit', 'Izin'])) {
                    if ($this->validateDate($r[2], "H:i:s") == false) {
                        $err['jam_masuk' . $no] = ['Baris ' . $no . ' Format Waktu Jam Masuk Tidak Sesuai (H:i:s).'];
                    }

                    if ($this->validateDate($r[3], "H:i:s") == false) {
                        $err['jam_keluar' . $no] = ['Baris ' . $no . ' Format Waktu Jam Keluar Tidak Sesuai (H:i:s).'];
                    }
                }
                // dd($r[0]);
            }
        }


        // dd($err);
        if (count($err) > 0) {
            return response()->json([
                'message' => "Data Absen tidak valid.",
                'errors'  => $err,
                'id' => $request->id
            ], 422);
        }

        return response()->json([
            'message' => "Success.",
        ], 200);
    }

    public function importAbsenStoreStep2(Request $request)
    {
        $request->validate([
            'fileExcel' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        $file = $request->file('fileExcel');
        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls', 'XLSX', 'XLS'])) {
            return response()->json([
                'message' => "Terdapat kesalahan saat menyimpan data.<br/>Error Allow Extension."
            ], 422);
        }
        $user_id = $request->id;

        $rows = Excel::toArray(new PresensiImport(), $request->file('fileExcel'));

        foreach ($rows[0] as $k => $r) {
            $no = $k + 1;
            if ($k != 0) {
                $myDateTime = DateTime::createFromFormat('d/m/Y', $r[0]);
                $newDateString = $myDateTime->format('Y-m-d');
                // $present = Present::whereuser_id($user_id)->wheretanggal($newDateString)->first();
                Present::updateOrCreate(['user_id' => $user_id, 'tanggal' => $newDateString], [
                    'keterangan' => $r[1],
                    'jam_masuk' => $r[2] != '-' ? $r[2] : null,
                    'jam_keluar' => $r[3] != '-' ? $r[3] : null,
                    'updated_by' => Auth::user()->username
                ]);
            }
        }

        return response()->json([
            'message' => 'Import Presensi berhasil tersimpan.'
        ]);
    }



    public function validateDate($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
}
