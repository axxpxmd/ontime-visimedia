<?php

namespace App\Http\Controllers;

use App\ConfigApp;
use App\Pengumuman;
use App\SubUnitKerja;
use App\UnitKerjaa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class ConfigAppController extends Controller
{
    public function index()
    {
        $config = ConfigApp::first();
        return view('config.index', compact('config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'string|required',
            'pemilik' => 'string|required',
            'app_id' => 'string|required',
        ]);
        $config = ConfigApp::first();
        $path = $config->icon;
        if ($request->file('icon')) {
            if ($config->icon != 'default.jpg') {
                if (Storage::disk('sftp')->exists($config->icon)) {
                    Storage::disk('sftp')->delete($config->icon);
                }
            }

            $name = time().".".$request->file('icon')->getClientOriginalExtension();
            $img = Image::make($request->file('icon'))->stream();
            $upload_path =  'file-config/';
            $image_url = $upload_path.$name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $path = $image_url;
        }

        $config->update([
            'nama' => $request->nama,
            'pemilik' => $request->pemilik,
            'app_id' => $request->app_id,
            'icon' => $path
        ]);

        return redirect()->back()->with('success', 'Berhasil diperbarui');
    }

    public function getUnit(Request $request)
    {
        if ($request->opd_id) {
            return response()->json(UnitKerjaa::whereopd_id($request->opd_id)->get());
        }
        return response()->json(UnitKerjaa::all());
    }
    public function getSubUnit(Request $request)
    {
        if ($request->unit_kerja_id) {
            return response()->json(SubUnitKerja::whereunit_kerja_id($request->unit_kerja_id)->get());
        }
        return response()->json(SubUnitKerja::all());
    }

    public function pengumuman()
    {
        $config = Pengumuman::first();
        return view('config.pengumuman', compact('config'));
    }
    public function pengumuman_store(Request $request)
    {
        $request->validate([
            'pengumuman' => 'mimes:jpg,jpeg,png',

        ]);
        $config = Pengumuman::first();
        $path = $config->path;
        if ($request->file('pengumuman')) {
            if ($config->path != 'default.jpg') {
                if (Storage::disk('sftp')->exists($config->path)) {
                    Storage::disk('sftp')->delete($config->path);
                }
            }

            $name = time().".".$request->file('pengumuman')->getClientOriginalExtension();
            $img = Image::make($request->file('pengumuman'))->stream();
            $upload_path =  'file-pengumuman/';
            $image_url = $upload_path.$name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $path = $image_url;
        }

        $config->update([
            'status' =>$request->status,
            'path' => $path
        ]);

        return redirect()->back()->with('success', 'Berhasil diperbarui');
    }
}
