@extends('layouts.app')

@section('title')
Sanksi {{ $sanksi->tmsanksi->nama }} -  {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Sanksi {{ $sanksi->tmsanksi->nama }}</h5>
                <a href="{{ route('sanksi.index',$sanksi->tmsanksi_id) }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>

            </div>
            <div class="card-body">
                <form action=" {{ route('sanksi.update', $sanksi->id) }} " method="post" enctype="multipart/form-data">
                    @method('patch')
                    @csrf
                    <input type="hidden" name="id" value="{{ $sanksi->id }}">

                    <div class="form-group row">
                        <div class="col-sm-3"><label for="nama" class="float-right col-form-label">Nama</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ $sanksi->nama }}" readonly>
                            @error('nama') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="percent" class="float-right col-form-label">Percent</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('percent') is-invalid @enderror" id="percent" name="percent" value="{{ $sanksi->percent }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  >
                            @error('percent') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>



                    <div class="form-group row justify-content-end">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-success btn-block">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
<!-- /.container-fluid -->

@endsection

@push('scripts')
<script>

$('.timepicker').datetimepicker({
    datepicker:false,
  format:'H:i:s',
  scrollMonth : false,
            scrollInput : false
});
</script>
@endpush
