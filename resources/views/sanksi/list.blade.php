@extends('layouts.app')

@section('title')
Sanksi  - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Sanksi </h5>
                <a href="{{ route('tmsanksi.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>


                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sanksi as $k => $s)
                            <tr>
                                <th>{{ $k+1 }}</th>
                                <td>{{ $s->nama }}</td>

                                <td>
                                    @if ($s->created_by == auth()->user()->id && $s->id != 1)
                                    <a href="{{route('tmsanksi.edit',$s->id)}}" class="btn btn-sm btn-warning" title="Edit Sanksi"><i class="fas fa-pencil-alt"></i></a>
                                    <form class="d-inline-block" action="{{ route('tmsanksi.destroy', $s->id) }}"
                                        method="post">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus sanksi ini ???')"><i
                                                class="fas fa-trash"></i></button>
                                         </form>
                                    @endif
                                    <a href="{{route('sanksi.index',$s->id)}}" class="btn btn-sm btn-info" title="Show Sanksi"><i class="fas fa-clock"></i></a>
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
