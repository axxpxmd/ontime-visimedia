@extends('layouts.app')

@section('title')
Detail Unit Kerja - {{ config('app.name') }}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Detail Unit Kerja</h5>
                        <a href="{{ route('unitkerjas.index') }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td>Nama Unit Kerja</td><td>: {{ $uker->unit_kerja }}</td></tr>
                                    <tr><td>Initial</td><td>: {{ $uker->initial }}</td></tr>
                                </tbody>
                            </table>
                            <div class="float-right">
                                <a href="{{ route('unitkerjas.edit',$uker) }}" class="btn btn-sm btn-success" title="Ubah"><i class="fas fa-edit"></i></a>
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