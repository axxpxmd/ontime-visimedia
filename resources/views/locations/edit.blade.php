@extends('layouts.app')
@section('title')
Ubah Lokasi Presensi - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Ubah Lokasi Presensi</h5>
                    <a href="{{ route('locations.index',$uker) }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('locations.update', $uker->id) }} " method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-5"><label for="nama_lokasi" class="float-right col-form-label">Nama Lokasi</label></div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" id="nama_lokasi" name="nama_lokasi" value="{{ $uker->nama_lokasi }}">
                                @error('nama_lokasi') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5"><label for="latitude" class="float-right col-form-label">Latitude</label></div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ $uker->latitude }}">
                                @error('latitude') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5"><label for="longitude" class="float-right col-form-label">Longitude</label></div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ $uker->longitude }}">
                                @error('longitude') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-success btn-block">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('document').ready(function(){
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });
</script>
@endpush