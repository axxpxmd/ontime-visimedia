<?php

namespace App\Http\Controllers;

use App\Sanksi;
use App\Tmsanksi;
use Illuminate\Http\Request;

class SanksiController extends Controller
{
    public function access($id)
    {
        $auth = auth()->user();
        if ($auth->role_id ==  7) {
            if ($id != $auth->id) {
                return false;
            }
        }
        return true;
    }
    public function index($id)
    {
        $sanksi = Sanksi::wheretmsanksi_id($id)->get();
        return view('sanksi.index', compact('sanksi'));
    }
    public function edit($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        if (!$this->access($sanksi->created_by)) {
            return redirect()->route('tmsanksi.index');
        }

        return view('sanksi.edit', compact('sanksi'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'percent'  => ['required'],

        ]);
        $sanksi = Sanksi::whereid($id)->first();
        $sanksi->update([
            'percent' => $request->percent,

        ]);
        return redirect()->back()->with('success', 'Sanksi berhasil diperbarui');
    }
    public function list()
    {
        $sanksi = Tmsanksi::all();

        return view('sanksi.list', compact('sanksi'));
    }
    public function edit_tmsanksi($id)
    {
        $sanksi = Tmsanksi::findOrFail($id);
        if (!$this->access($sanksi->created_by)) {
            return redirect()->route('tmsanksi.index');
        }
        return view('sanksi.tmsanksi-edit', compact('sanksi'));
    }
    public function destroy_tmsanksi($id)
    {
        $tmsanksi = Tmsanksi::findOrFail($id);
        if (!$this->access($tmsanksi->created_by)) {
            return redirect()->route('tmsanksi.index');
        }
        $nama = $tmsanksi->nama;
        if ($tmsanksi->users()->count() >0) {
            return redirect('/tmsanksi')->with('error', 'Sanksi "'.$nama.'" Telah digunakan.');
        }

        if (isset($tmsanksi)) {
            $tmsanksi->sanksi()->delete();
            $tmsanksi->delete();
        }
        return redirect('/tmsanksi')->with('success', 'Sanksi "'.$nama.'" berhasil dihapus');
    }
    public function update_tmsanksi(Request $request, $id)
    {
        $tmsanksi = Tmsanksi::whereid($id)->first();
        if (!$this->access($tmsanksi->created_by)) {
            return redirect()->route('tmsanksi.index');
        }
        $tmsanksi->update([
            'nama' => $request->name,

        ]);
        return redirect()->back()->with('success', 'Sanksi berhasil diperbarui');
    }

    public function create_tmsanksi()
    {
        return view('sanksi.tmsanksi-create');
    }
    public function store_tmsanksi(Request $request)
    {
        $tmsanksi = Tmsanksi::create([
            'nama' => $request->name,

        ]);

        $sanksi = Sanksi::whereIn('id', [1,2,3,4,5])->get();
        foreach ($sanksi as $i) {
            Sanksi::create([
                'nama' => $i->nama,
                'percent' => $i->percent,
                'tmsanksi_id' => $tmsanksi->id

            ]);
        }
        return redirect()->back()->with('success', 'Sanksi berhasil ditambah');
    }
}
