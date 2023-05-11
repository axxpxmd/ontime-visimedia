@extends('layouts.app')

@section('title')
Detail Lokasi Presensi - {{ config('app.name') }}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Detail Lokasi Presensi</h5>
                        <a href="{{ route('locations.index') }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td>Nama Lokasi</td><td>: {{ $uker->nama_lokasi }}</td></tr>
                                    <tr><td>Latitude</td><td>: {{ $uker->latitude }}</td></tr>
                                    <tr><td>Longitude</td><td>: {{ $uker->longitude }}</td></tr>
                                </tbody>
                            </table>
                            <div class="float-right">
                                <a href="{{ route('locations.edit',$uker) }}" class="btn btn-sm btn-success" title="Ubah"><i class="fas fa-edit"></i></a>
                                {{-- @if ($user->id != auth()->user()->id)
                                    <form class="d-inline-block" action="{{ route('users.destroy',$user) }}" method="post">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah anda yakin ingin menghapus user ini ???')"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection