<?php

namespace App\Http\Controllers;

use App\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class HariLiburController extends Controller
{
    public function api()
    {


        $harilibur = HariLibur::all();
        // dd($harilibur);

        return DataTables::of($harilibur)
        ->editCOlumn('tgl',function($p){
            return Carbon::parse($p->tgl)->isoFormat('D MMMM Y');
        })
            ->addColumn('action', function ($p) {
                return "
					<a onclick='edit(".$p->id.")'  href='javascript:;' title='Edit Hari Libur'><i class='fas fa-pencil-alt mr-1'></i></a>
					<a href='#' onclick='remove(".$p->id.")' class='text-danger' title='Hapus Hari Libur'><i class='fas fa-trash'></i></a>";


            })



            ->rawColumns(['action'])
            ->toJson();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('harilibur.index');
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
            'nama' => 'required',
            'tgl' => 'required',

		]);

        if(HariLibur::where('tgl', $request->input('tgl'))->count() > 0)
        {
            $err = ['tgl' => ["Tanggal sudah pernah ditambah"]];
            return response()->json([
                'message' => "Error.",
                'errors'  => $err
            ], 422);
        }

        $input = $request->all();
        HariLibur::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data hari libur berhasil tersimpan.'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HariLibur  $hariLibur
     * @return \Illuminate\Http\Response
     */
    public function show(HariLibur $hariLibur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HariLibur  $hariLibur
     * @return \Illuminate\Http\Response
     */
    public function edit(HariLibur $hariLibur)
    {
        return $hariLibur;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HariLibur  $hariLibur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HariLibur $hariLibur)
    {
        $request->validate([
            'nama' => 'required',
            'tgl' => 'required',

		]);

        if(HariLibur::where('tgl', $request->input('tgl'))->where('id','!=',$hariLibur->id)->count() > 0)
        {
            $err = ['tgl' => ["Tanggal sudah pernah ditambah"]];
            return response()->json([
                'message' => "Error.",
                'errors'  => $err
            ], 422);
        }

        $input = $request->all();
        $hariLibur->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Data hari libur berhasil diperbaharui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HariLibur  $hariLibur
     * @return \Illuminate\Http\Response
     */
    public function destroy(HariLibur $hariLibur)
    {
        $hariLibur->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data hari libur berhasil dihapus.'
        ]);

    }
}
