@extends('layouts.app')

@section('title')
Jam Kerja  - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Jam Kerja {{ $shift->name }}</h5>
                <a href="{{ route('jamkerja.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hari</th>
                                <th>Mulai Absen</th>
                                <th>Mulai Kerja</th>
                                <th>Mulai Sanksi</th>
                                <th>Mulai Sanksi 2</th>
                                <th>Maks Absen</th>
                                <th>Mulai Checkout</th>
                                <th>Selesai Kerja</th>
                                {{-- <th>Unit Kerja</th> --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jamkerja as $k => $jk)
                            <tr>
                                <th>{{ $k+1 }}</th>
                                <td>{{ $jk->hari }}</td>
                                <td>{{ $jk->mulai_absen }}</td>
                                <td>{{ $jk->mulai_kerja }}</td>
                                <td>{{ $jk->mulai_sanksi }}</td>
                                <td>{{ $jk->mulai_sanksi2 }}</td>
                                <td>{{ $jk->maks_absen }}</td>
                                <td>{{ $jk->mulai_checkout }}</td>
                                <td>{{ $jk->selesai_kerja }}</td>
                                {{-- @foreach ($ukers as $uker)
                                    @if ($user->uker_id==$uker->id)
                                        <td>{{ $uker->unit_kerja }}</td>
                                    @endif
                                @endforeach --}}
                                <td>
                                    {{-- @if ($jk->created_by == auth()->user()->id) --}}
                                    <a href="{{route('jamkerja.edit',$jk->id)}}" class="btn btn-sm btn-warning" title="Detail User"><i class="fas fa-pencil-alt"></i></a>
                                    {{-- @endif --}}
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
