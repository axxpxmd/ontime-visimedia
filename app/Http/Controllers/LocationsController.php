<?php

namespace App\Http\Controllers;

use App\User;
use App\Present;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total  = Location::all();
        $locations  = Location::paginate(10); // uker = unit kerja
        $rank   = $locations->firstItem();
        return view('locations.index', compact('total', 'locations', 'rank'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.create');
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
            'nama_lokasi'   => ['required'],
            'latitude'      => ['required'],
            'longitude'     => ['required']
        ]);

        Location::create($uker);
        return redirect('/locations')->with('success', 'Lokasi presensi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $uker = Location::findOrFail($id);
        if (!$this->access($uker)) {
            return redirect()->route('locations.index');
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
        return view('locations.show', compact('uker'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uker = Location::find($id);
        if (!checkCreatedBy($uker)) {
            return redirect()->route('locations.index');
        }
        return view('locations.edit', compact('uker'));
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
        $uker = Location::find($id);
        $request->validate([
            'nama_lokasi'   => ['required'],
            'latitude'      => ['required'],
            'longitude'     => ['required']
        ]);

        $uker->update([
            'nama_lokasi'   => $request->nama_lokasi,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude
         ]);
        return redirect()->back()->with('success', 'Lokasi presensi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $nama = $location->nama_lokasi;
        if (!checkCreatedBy($location)) {
            return redirect()->route('locations.index');
        }
        Location::destroy($location->id);
        return redirect('/locations')->with('success', 'Lokasi presensi "'.$location->nama_lokasi.'" berhasil dihapus');
    }

    public function search(Request $request)
    {
        $request->validate([
            'cari' => ['required']
        ]);
        $ukers = Location::where('nama_lokasi', 'like', '%'.$request->cari.'%')
                    ->orWhere('latitude', 'like', '%'.$request->cari.'%')
                    ->orWhere('longitude', 'like', '%'.$request->cari.'%')
                    ->paginate(10);
        $total  =$ukers;
        $rank = $ukers->firstItem();
        return view('locations.index', compact('ukers', 'rank', 'total', ));
    }
}
