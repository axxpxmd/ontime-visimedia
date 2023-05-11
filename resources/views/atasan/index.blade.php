@extends('layouts.app')

@section('title')
Managemen Pegawai - {{ config('app.name') }}
@endsection

@section('header')
   
@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Daftar Pegawai ({{ $total }})</h5>
                {{-- <a href="{{ route('atasan.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{ route('atasan.search') }}" method="get">
                            <input type="text" name="cari" id="cari" class="form-control mb-3" value="{{ request('cari') }}" placeholder="Cari . . ." autocomplete="off">
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="float-right">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>

                                <th>Nama</th>
                                <th>Role</th>
                                {{-- <th>Unit Kerja</th> --}}
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                           
                                <tr>
                                    <th>{{ $rank++ }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->role->role }}</td>
                                 
                                    <td>
                                        <a href="{{ route('atasan.show', $user) }}" class="btn btn-sm btn-info" title="Detail User"><i class="fas fa-eye"></i></a>
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
