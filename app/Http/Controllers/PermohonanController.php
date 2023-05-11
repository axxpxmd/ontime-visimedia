<?php

namespace App\Http\Controllers;

use Image;
use App\User;
use App\Present;
use App\Permohonan;
use Carbon\CarbonPeriod;
use App\PersonalInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PermohonanController extends Controller
{
    public function lst($cari = null){
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
    public function api()
    {
        $list = $this->lst();
        $list[]= auth()->user()->id;
     
        $permohonan = Permohonan::whereIn('user_id',$list)->orderBy('tanggal_dari', 'desc')->orderBy('status')->get();
        // dd($permohonan);

        return DataTables::of($permohonan)
            ->editColumn('tanggal_dari', function ($p) {
                return Carbon::parse($p->tanggal_dari)->isoFormat('D MMMM Y');
            })
            ->editColumn('tanggal_sampai', function ($p) {
                return Carbon::parse($p->tanggal_sampai)->isoFormat('D MMMM Y');
            })
            ->editColumn('status', function ($p) {
                if ($p->status == 1) {
                    return '<span class="badge badge-primary">Menunggu</span>';
                } elseif ($p->status == 2) {
                    return '<span class="badge badge-success">Disetujui</span>';
                } else {
                    return '<span class="badge badge-danger">Ditolak</span>';
                }
            })
            ->addColumn('action', function ($p) {
                $html = "";
                if ($p->status == 1) {
                    $html .= "
                <a onclick='edit(" . $p->id . ")'  href='javascript:;' title='Edit Permohonan'><i class='fas fa-pencil-alt mr-1'></i></a>
                <a href='#' onclick='remove(" . $p->id . ")' class='text-danger' title='Hapus Permohonan'><i class='fas fa-trash'></i></a>";
                }

                return $html;
            })



            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permohonan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'tanggal_dari' => 'required|unique:permohonans,tanggal_dari',
            'tanggal_sampai' => 'required|unique:permohonans,tanggal_sampai',


        ]);



        $input = $request->all();
        $input['status'] = 1;
        $input['user_id'] = Auth::user()->id;
        if ($request->file('file')) {
            $name = time() . "." . $request->file('file')->getClientOriginalExtension();
            $img =  Image::make($request->file('file'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'file-permohonan/';
            $image_url = $upload_path . $name;
            Storage::disk('sftp')->put($image_url, $img);

            $input['file'] = $image_url;
        }
        // dd($input);
        Permohonan::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data Permohonan berhasil tersimpan.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function show(Permohonan $permohonan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function edit(Permohonan $permohonan)
    {
        return $permohonan;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'jenis' => 'required',
            'tanggal' => 'required|unique:permohonans,tanggal,' . $permohonan->id,


        ]);



        $input = $request->all();
        $input['status'] = 1;
        if ($request->file('file')) {
            $name = time() . "." . $request->file('file')->getClientOriginalExtension();
            $img = Image::make($request->file('file'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'file-permohonan/';
            $image_url = $upload_path . $name;
            Storage::disk('sftp')->put($image_url, $img);

            $input['file'] = $image_url;
        }

        $permohonan->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Data Permohonan berhasil diubah.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permohonan $permohonan)
    {
        $permohonan->delete();
        if (Storage::disk('sftp')->exists($permohonan->file)) {
            Storage::disk('sftp')->delete($permohonan->file);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Permohonan berhasil dihapus.'
        ]);
    }

    //Atasan

    public function api_list()
    {
     
        $list = $this->lst();
        $list[]= auth()->user()->id;
        $permohonan = Permohonan::whereIn('user_id',$list)->orderBy('tanggal_dari', 'desc')->orderBy('status')->get();
       


        return DataTables::of($permohonan)
            ->editColumn('tanggal_dari', function ($p) {
                return Carbon::parse($p->tanggal_dari)->isoFormat('D MMMM Y');
            })
            ->editColumn('tanggal_sampai', function ($p) {
                return Carbon::parse($p->tanggal_sampai)->isoFormat('D MMMM Y');
            })
            ->editColumn('status', function ($p) {
                if ($p->status == 1) {
                    return '<span class="badge badge-primary">Menunggu</span>';
                } elseif ($p->status == 2) {
                    return '<span class="badge badge-success">Disetujui</span>';
                } else {
                    return '<span class="badge badge-danger">Ditolak</span>';
                }
            })
            ->addColumn('action', function ($p) {
                $html = "";

                $html .= "
                <a onclick='edit(" . $p->id . ")'  href='javascript:;' title='Edit Permohonan'><i class='fas fa-pencil-alt mr-1'></i></a>
               ";


                return $html;
            })
            ->addColumn('nama', function ($p) {
                return $p->user->nama;
            })



            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function list()
    {
        return view('permohonan.list');
    }

    public function list_show($id)
    {
        return Permohonan::whereid($id)->first();
    }

    public function list_update(Request $request, $id)
    {
        $permohonan = Permohonan::whereid($id)->first();
        if ($request->status == 2) {
            if ($permohonan->tanggal_dari == $permohonan->tanggal_sampai) {
                Present::updateOrCreate(
                    ["tanggal" => $permohonan->tanggal_dari, "user_id" => $permohonan->user_id,],
                    [
                        "keterangan" => $permohonan->jenis,
                        "status_permohonan" => 1
                    ]
                );
            } else {
                $period = CarbonPeriod::create($permohonan->tanggal_dari, $permohonan->tanggal_sampai);

                // Iterate over the period
                foreach ($period as $date) {
                    $date =  $date->format('Y-m-d');
                    Present::updateOrCreate(
                        ["tanggal" => $date, "user_id" => $permohonan->user_id,],
                        [
                            "keterangan" => $permohonan->jenis,
                            "status_permohonan" => 1
                        ]
                    );
                }
            }
        } else {
            // if($permohonan->tanggal_dari == $permohonan->tanggal_sampai){
            //     $present = Present::where('tanggal','=',$permohonan->tanggal_dari)->whereuser_id($permohonan->user_id)->first();
            //     if($present){
            //         $present->delete();
            //     }
            // }else{
            //     $period = CarbonPeriod::create($permohonan->tanggal_dari, $permohonan->tanggal_sampai);

            //     // Iterate over the period
            //     foreach ($period as $date) {
            //         $date =  $date->format('Y-m-d');
            //         $present = Present::where('tanggal','=',$date)->whereuser_id($permohonan->user_id)->first();
            //         if($present){
            //             $present->delete();
            //         }
            //     }
            // }
        }

        $permohonan->update([
            "status" => $request->status,
            "keterangan_atasan" => $request->keterangan_atasan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Permohonan berhasil diubah.'
        ]);
    }
}
