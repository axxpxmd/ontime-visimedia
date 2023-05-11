@extends('layouts.app')

@section('title')
Lokasi Presensi Management - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Lokasi Presensi ({{ $total->count() }})</h5>
                <a href="{{ route('locations.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{ route('locations.search') }}" method="get">
                            <input type="text" name="cari" id="cari" class="form-control mb-3" value="{{ request('cari') }}" placeholder="Cari . . ." autocomplete="off">
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="float-right">
                            {{ $locations->links() }}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Lokasi</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)
                                <tr>
                                    <th>{{ $rank++ }}</th>
                                    <td>{{ $location->nama_lokasi }}</td>
                                    <td>{{ $location->latitude }}</td>
                                    <td>{{ $location->longitude }}</td>
                                    <td>
                                        @if ($location->created_by == auth()->user()->id)
                                        <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-info" title="Detail Lokasi Presensi"><i class="fas fa-eye"></i></a>
                                        <form class="d-inline-block" action="{{ route('locations.destroy', $location) }}"
                                        method="post">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus lokasi ini ???')"><i
                                                class="fas fa-trash"></i></button>
                                         </form>
                                        @else

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- /.container-fluid -->

@endsection
