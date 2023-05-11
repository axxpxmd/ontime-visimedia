@extends('layouts.app')

@section('title')
Unit Kerja Management - {{ config('app.name') }}
@endsection

@section('header')
   
@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Unit Kerja ({{ $total->count() }})</h5>
                <a href="{{ route('unitkerjas.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{ route('unitkerjas.search') }}" method="get">
                            <input type="text" name="cari" id="cari" class="form-control mb-3" value="{{ request('cari') }}" placeholder="Cari . . ." autocomplete="off">
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="float-right">
                            {{ $ukers->links() }}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Unit Kerja</th>
                                <th>Initial</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ukers as $uker)
                                <tr>
                                    <th>{{ $rank++ }}</th>
                                    <td>{{ $uker->unit_kerja }}</td>
                                    <td>{{ $uker->initial }}</td>
                                    <td>
                                        <a href="{{ route('unitkerjas.edit', $uker) }}" class="btn btn-sm btn-info" title="Detail Unit Kerja"><i class="fas fa-eye"></i></a>

                                        <a  onclick="remove({{$uker->id}})" class="btn btn-sm btn-danger" title="Hapus Unit Kerja"><i class="fas fa-trash text-white"></i></a>


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
@push('scripts')
<script>
    function remove(id) {
            $.confirm({
                title: '',
                content: 'Apakah Anda yakin akan menghapus data ini?',
                icon: 'icon icon-question amber-text',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    ok: {
                        text: "ok!",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function() {
                            $.ajax({
                                url: "{{ route('unitkerjas.destroy', ':id') }}".replace(':id', id),
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': '{{ csrf_token() }}',
                                },
                                success: function(data) {
                                  location.reload();

                                },
                                error: function() {
                                    console.log('Opssss...');
                                    reload();
                                }
                            });
                        }
                    },
                    cancel: function() {
                        console.log('the user clicked cancel');
                    }
                }
            });
        }
</script>
@endpush
