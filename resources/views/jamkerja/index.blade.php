@extends('layouts.app')

@section('title')
Shift  - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Shift </h5>
                <a href="{{ route('shift.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>


                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shifts as $k => $jk)
                            <tr>
                                <th>{{ $k+1 }}</th>
                                <td>{{ $jk->name }}</td>

                                <td>
                                   
                                    <a href="{{route('shift.edit',$jk->id)}}" class="btn btn-sm btn-warning" title="Edit Shift"><i class="fas fa-pencil-alt"></i></a>
                                    <form class="d-inline-block" action="{{ route('shift.destroy', $jk->id) }}"
                                        method="post">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus shift ini ???')"><i
                                                class="fas fa-trash"></i></button>
                                         </form>
                                    <a href="{{route('jamkerja.jam_kerja',$jk->id)}}" class="btn btn-sm btn-info" title="Show Jam Kerja"><i class="fas fa-clock"></i></a>
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
