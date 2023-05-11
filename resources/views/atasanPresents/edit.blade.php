@extends('layouts.app')
@section('title')
Tinjau Kehadiran - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Tinjau Kehadiran</h5>
                    <a href="{{ route('atasanPresents.index',$presents) }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('atasanPresents.update', $presents->id) }} " method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="tgl_aktifitas" class="float-right col-form-label">Tanggal</label></div>
                            <div class="col-sm-10">
                                <input disabled type="date" class="form-control @error('tgl_aktifitas') is-invalid @enderror" id="tgl_aktifitas" name="tgl_aktifitas" value="{{ $presents->tanggal }}">
                                @error('tgl_aktifitas') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="nama" class="float-right col-form-label">Nama</label></div>
                            <div class="col-sm-10">
                                <input disabled type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ $presents->user->nama }}">
                                @error('nama') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="keterangan" class="float-right col-form-label">Keterangan</label></div>
                            <div class="col-sm-10">
                                <input disabled type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ $presents->keterangan }}">
                                @error('keterangan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <img id="image" src="{{ Storage::url($presents->foto_permohonan) }}" alt="{{ $presents->foto_permohonan }}" class="img-thumbnail mb-1">
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="col-sm-2"><label for="keterangan_atasan" class="float-right col-form-label">Keterangan</label></div>
                            <div class="col-sm-10">
                                <textarea name="keterangan_atasan" id="keterangan_atasan" cols="30" rows="5" class="form-control @error('keterangan_atasan') is-invalid @enderror">{{ $presents->keterangan_atasan }}</textarea>

                                @error('keterangan_atasan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-6">
                                <input type="submit" name="setujui" class="btn btn-success btn-block" value="Setujui">
                            </div>
                            <div class="col-sm-6">
                                <input type="submit" name="tolak" class="btn btn-danger btn-block" value="Tolak">
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
