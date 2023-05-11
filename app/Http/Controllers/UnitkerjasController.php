<?php

namespace App\Http\Controllers;

use App\User;
use App\Present;
use App\Unitkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UnitkerjasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total  = Unitkerja::all();
        $ukers  = Unitkerja::paginate(10); // uker = unit kerja
        $rank   = $ukers->firstItem();
        return view('unitkerjas.index', compact('total', 'ukers', 'rank'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unitkerjas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uker = $request->validate([
            'unit_kerja'    => ['required'],
            'initial'       => ['required']
        ]);

        Unitkerja::create($uker);
        return redirect('/unitkerjas')->with('success', 'Unit kerja berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $uker = Unitkerja::findOrFail($id);

        if (!checkCreatedBy($uker)) {
            return redirect()->route('unit_kerja.index');
        }
        // $presents = Present::whereUserId($uker->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->orderBy('tanggal','desc')->paginate(5);
        // $masuk = Present::whereUserId($uker->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('masuk')->count();
        // $telat = Present::whereUserId($uker->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('telat')->count();
        // $cuti = Present::whereUserId($uker->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('cuti')->count();
        // $alpha = Present::whereUserId($uker->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('alpha')->count();
        // $kehadiran = Present::whereUserId($uker->id)->whereMonth('tanggal',date('m'))->whereYear('tanggal',date('Y'))->whereKeterangan('telat')->get();
        // $totalJamTelat = 0;
        // foreach ($kehadiran as $present) {
        //     $totalJamTelat = $totalJamTelat + (\Carbon\Carbon::parse($present->jam_masuk)->diffInHours(\Carbon\Carbon::parse('07:00:00')));
        // }
        // $url = 'https://kalenderindonesia.com/api/YZ35u6a7sFWN/libur/masehi/'.date('Y/m');
        // $kalender = file_get_contents($url);
        // $kalender = json_decode($kalender, true);
        // $libur = false;
        // $holiday = null;
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

        // dd($uker);
        return view('unitkerjas.show', compact('uker'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uker = Unitkerja::find($id);
        return view('unitkerjas.edit', compact('uker'));
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
        $uker = Unitkerja::find($id);
        $request->validate([
            'unit_kerja'    => ['required'],
            'initial'       => ['required']
        ]);

        $uker->update([
            'unit_kerja' => $request->unit_kerja,
            'initial'   => $request->initial
         ]);
        return redirect()->back()->with('success', 'Unit kerja berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $uker = Unitkerja::whereid($id)->firstOrFail();
        $nama = $uker->unit_kerja;

        Unitkerja::destroy($uker->id);
        return response()->json([
            'success' => true,
            'message' => 'Unit kerja "'.$uker->unit_kerja.'" berhasil dihapus'
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'cari' => ['required']
        ]);

        $ukers = Unitkerja::where('unit_kerja', 'like', '%'.$request->cari.'%')
                    ->orWhere('initial', 'like', '%'.$request->cari.'%');

        $total  = $ukers;
        $ukers = $ukers->paginate(10);
        $rank = $ukers->firstItem();
        return view('unitkerjas.index', compact('ukers', 'rank', 'total'));
    }
}
