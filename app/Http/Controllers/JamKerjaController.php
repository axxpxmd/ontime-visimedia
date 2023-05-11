<?php

namespace App\Http\Controllers;

use App\JamKerja;
use App\Shift;
use Illuminate\Http\Request;

class JamKerjaController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('jamkerja.index', compact('shifts'));
    }
    public function jam_kerja($id)
    {
        $shift = Shift::findOrFail($id);
        $jamkerja = JamKerja::where('shift_id', $id)->get();
        return view('jamkerja.jam-kerja', compact('jamkerja', 'shift'));
    }
    public function edit($id)
    {
        $jamkerja = JamKerja::findOrFail($id);
        return view('jamkerja.edit', compact('jamkerja'));
    }
    public function create()
    {
        return view('jamkerja.create');
    }
    public function update(Request $request, $id)
    {
        $jamkerja = JamKerja::whereid($id)->first();
        $jamkerja->update([
            'mulai_absen' => $request->mulai_absen,
            'mulai_kerja' => $request->mulai_kerja,
            'mulai_sanksi' => $request->mulai_sanksi,
            'mulai_sanksi2' => $request->mulai_sanksi2,
            'maks_absen' => $request->maks_absen,
            'selesai_kerja' => $request->selesai_kerja,
            'mulai_checkout' => $request->mulai_checkout
        ]);
        return redirect()->back()->with('success', 'Jam Kerja berhasil diperbarui');
    }

    public function edit_shift($id)
    {
        $shift = Shift::findOrFail($id);
        return view('jamkerja.edit_shift', compact('shift'));
    }
    public function update_shift(Request $request, $id)
    {
        $shift = Shift::whereid($id)->first();
        $shift->update([
            'name' => $request->name,

        ]);
        return redirect()->back()->with('success', 'Shift berhasil diperbarui');
    }
    public function store_shift(Request $request)
    {
        $shift = Shift::create([
            'name' => $request->name,

        ]);

        $jamkerja = JamKerja::whereIn('id', [1,2,3,4,5])->get();
        foreach ($jamkerja as $i) {
            JamKerja::create([
                'shift_id' => $shift->id,
                'N' => $i->N,
                'hari' => $i->hari,
                'mulai_absen' => $i->mulai_absen,
                'mulai_kerja' => $i->mulai_kerja,
                'mulai_sanksi' => $i->mulai_sanksi,
                'mulai_sanksi2' => $i->mulai_sanksi2,
                'maks_absen' => $i->maks_absen,
                'selesai_kerja' => $i->selesai_kerja,
                'mulai_checkout' => $i->mulai_checkout
            ]);
        }
        return redirect()->back()->with('success', 'Shift berhasil ditambah');
    }

    public function destroy_shift($id)
    {
        $shift = Shift::findOrFail($id);
        $nama = $shift->nama;
        if (isset($shift)) {
            $shift->jamKerja()->delete();
            $shift->delete();
        }
        return redirect('/shift')->with('success', 'Shift "'.$nama.'" berhasil dihapus');
    }
}
