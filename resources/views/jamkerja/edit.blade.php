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
                <h5 class="m-0 pt-1 font-weight-bold float-left">Jam Kerja {{ $jamkerja->shift->name }}</h5>
                <a href="{{ route('jamkerja.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>

            </div>
            <div class="card-body">
                <form action=" {{ route('jamkerja.update', $jamkerja->id) }} " method="post" enctype="multipart/form-data">
                    @method('patch')
                    @csrf
                    <input type="hidden" name="id" value="{{ $jamkerja->id }}">

                    <div class="form-group row">
                        <div class="col-sm-3"><label for="hari" class="float-right col-form-label">Hari</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari" value="{{ $jamkerja->hari }}" readonly>
                            @error('hari') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3"><label for="mulai_absen" class="float-right col-form-label">Mulai Absen</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('mulai_absen') is-invalid @enderror" id="mulai_absen" name="mulai_absen" value="{{ $jamkerja->mulai_absen }}">
                            @error('mulai_absen') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="mulai_kerja" class="float-right col-form-label">Mulai Kerja</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('mulai_kerja') is-invalid @enderror" id="mulai_kerja" name="mulai_kerja" value="{{ $jamkerja->mulai_kerja }}">
                            @error('mulai_kerja') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="mulai_sanksi" class="float-right col-form-label">Mulai Sanksi</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('mulai_sanksi') is-invalid @enderror" id="mulai_sanksi" name="mulai_sanksi" value="{{ $jamkerja->mulai_sanksi }}">
                            @error('mulai_sanksi') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="mulai_sanksi2" class="float-right col-form-label">Mulai Sanksi 2</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('mulai_sanksi2') is-invalid @enderror" id="mulai_sanksi2" name="mulai_sanksi2" value="{{ $jamkerja->mulai_sanksi2 }}">
                            @error('mulai_sanksi2') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="maks_absen" class="float-right col-form-label">Maks Absen</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('maks_absen') is-invalid @enderror" id="maks_absen" name="maks_absen" value="{{ $jamkerja->maks_absen }}">
                            @error('maks_absen') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="mulai_checkout" class="float-right col-form-label">Mulai Checkout</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('mulai_checkout') is-invalid @enderror" id="mulai_checkout" name="mulai_checkout" value="{{ $jamkerja->mulai_checkout }}">
                            @error('mulai_checkout') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><label for="selesai_kerja" class="float-right col-form-label">Selesai Kerja </label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control timepicker @error('selesai_kerja') is-invalid @enderror" id="selesai_kerja" name="selesai_kerja" value="{{ $jamkerja->selesai_kerja }}">
                            @error('selesai_kerja') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
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
