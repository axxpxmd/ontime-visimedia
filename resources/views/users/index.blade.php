@extends('layouts.app')

@section('title')
Users Management - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Users ({{ $total->count() }})</h5>

                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a>

            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        {{-- <form class="form-inline" action="{{ route('users.search') }}" method="get">
                            <div class="form-group mb-2">
                                <input type="text" name="cari" id="cari" class="form-control mb-3" value="{{ request('cari') }}" placeholder="Cari . . ." autocomplete="off">

                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <select name="uker" id="" class="form-control mb-3">
                                    <option value="">Pilih</option>
                                        @foreach ($ukers as $item)
                                            <option value="{{ Auth::user()->uker_id }}" {{ Auth::user()->uker_id == $item->uker_id ? 'selected' : '' }}>{{ $item->unit_kerja }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                        </form> --}}

                        <form action="{{ route('users.search') }}" method="get" autocomplete="off">
                            <div class="form-row align-items-center">
                              <div class="col-sm-3 my-1">
                                <input type="text" name="cari" id="cari" class="form-control mb-3" value="{{ request('cari') }}" placeholder="Cari . . ." autocomplete="off">
                              </div>
                              <div class="col-sm-3 my-1">
                                <select class="form-control mb-3 select2 @error('opd_id') is-invalid @enderror" name="opd_id" id="opd_id"
                                onchange="getUnitKerja()">
                                <option value="">Pilih</option>
                                @foreach ($opds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == request('opd_id')  ? 'selected' : '' }}>{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                              </div>
                              <div class="col-sm-3 my-1">
                                <select class="form-control mb-3 select2 @error('unit_kerja_id') is-invalid @enderror" name="unit_kerja_id" id="unit_kerja_id"
                                    onchange="getSubUnitKerja()">
                                    <option value="">Pilih</option>

                                    </select>
                              </div>
                              <div class="col-sm-3 my-1">
                                <select class="form-control mb-3 select2 @error('subunit_kerja_id') is-invalid @enderror" name="subunit_kerja_id" id="subunit_kerja_id"
                                onchange="">
                                <option value="">Pilih</option>

                                </select>
                              </div>

                              {{-- @if (auth()->user()->role->role == 'Admin')
                              <div class="col-sm-5 my-1">
                                <select name="uker" id="" class="form-control mb-3">
                                    <option value="">Pilih</option>
                                        @foreach ($ukers as $item)
                                            <option value="{{ $item->id }}" {{ request('uker') == $item->id ? 'selected' : '' }}>{{ $item->unit_kerja }}</option>
                                        @endforeach
                                </select>
                              </div>
                              @endif --}}
                              <div class="col-auto my-1">
                                <button type="submit" class="btn btn-primary mb-3">Cari</button>
                              </div>
                            </div>
                          </form>

                    </div>

                </form>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th >OPD</th>
                                <th >Unit Kerja</th>
                                <th ">Sub Unit Kerja</th>
                                <th>Role</th>
                                {{-- <th>Unit Kerja</th> --}}
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <th>{{ $rank++ }}</th>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->personalInformation->Opd->nama ?? '-' }}</td>
                                <td>{{ $user->personalInformation->UnitKerja->nama ?? '-' }}</td>
                                <td width="150px">{{ $user->personalInformation->SubUnitKerja->nama ?? '-' }}</td>
                                <td>{{ $user->role->role }}</td>
                                {{-- @foreach ($ukers as $uker)
                                    @if ($user->uker_id==$uker->id)
                                        <td>{{ $uker->unit_kerja }}</td>
                                    @endif
                                @endforeach --}}
                                <td>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="Detail User"><i class="fas fa-eye"></i></a>
                                </td>
                                </tr>
                            @empty
                            <tr>
                                <th colspan="8">Tidak Ada Data</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    {{ $users->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
<!-- /.container-fluid -->

@endsection
@push('scripts')
<script>
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const unit_kerja_id = urlParams.get('unit_kerja_id');
const subunit_kerja_id = urlParams.get('subunit_kerja_id')

if(urlParams.has('opd_id')){
getUnitKerja(unit_kerja_id);
}
    function getUnitKerja(unit_kerja_id = null){
    val = $('#opd_id').val();

    option = "<option value=''>Pilih</option>";
    if(val == ""){
        $('#unit_kerja_id').html(option);
    }else{
        $('#unit_kerja_id').html("<option value=''>Loading...</option>");
        url = "{{ route('config.getUnit') }}";
        $.post(url,{ opd_id: val }, function(data){
            $.each(data, function( index, value ) {
                option += "<option value='" + value.id + "'>" + value.nama +"</li>";
            });
            $('#unit_kerja_id').html(option);
	if(unit_kerja_id != null){
		$('#unit_kerja_id').val(unit_kerja_id);
		getSubUnitKerja(subunit_kerja_id);
	}else{

            getSubUnitKerja();
}

        }, 'JSON');
    }
}
function getSubUnitKerja(subunit_kerja_id = null){
    val = $('#unit_kerja_id').val();

    option = "<option value=''>Pilih</option>";
    if(val == ""){
        $('#subunit_kerja_id').html(option);
    }else{
        $('#subunit_kerja_id').html("<option value=''>Loading...</option>");
        url = "{{ route('config.getSubUnit') }}";
        $.post(url,{ unit_kerja_id: val }, function(data){
            $.each(data, function( index, value ) {
                option += "<option value='" + value.id + "'>" + value.nama +"</li>";
            });
            $('#subunit_kerja_id').html(option);
		if(subunit_kerja_id != ''){
		 $('#subunit_kerja_id').val(subunit_kerja_id);
		}


        }, 'JSON');
    }
}
</script>

@endpush
