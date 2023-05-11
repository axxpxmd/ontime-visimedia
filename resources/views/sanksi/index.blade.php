@extends('layouts.app')

@section('title')
Sanksi {{ $sanksi->first()->tmsanksi->nama }}  - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Sanksi {{ $sanksi->first()->tmsanksi->nama }}</h5>
                <a href="{{ route('tmsanksi.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Sanksi</th>

                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sanksi as $k => $jk)
                            <tr>
                                <th>{{ $k+1 }}</th>
                                <td>{{ $jk->nama }}</td>
                                <td>{{ $jk->percent.'%'}}</td>

                                <td>
                                    @if ($jk->created_by == auth()->user()->id || auth()->user()->id == 1)
                                    <a href="{{route('sanksi.edit',$jk->id)}}" class="btn btn-sm btn-warning" title="Detail User"><i class="fas fa-pencil-alt"></i></a>
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
