@extends('layouts.app')
@section('title')
Ubah Aktifitas - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Ubah Aktifitas</h5>
                    <a href="{{ route('activities.index',$activitie) }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('activities.update', $activitie->id) }} " method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="aktifitas" class="float-right col-form-label">Aktifitas Harian</label></div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('aktifitas') is-invalid @enderror" id="aktifitas" name="aktifitas" value="{{ $activitie->aktifitas }}">
                                @error('aktifitas') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="tgl_aktifitas" class="float-right col-form-label">Tanggal Aktifitas</label></div>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tgl_aktifitas') is-invalid @enderror" id="tgl_aktifitas" name="tgl_aktifitas" value="{{ $activitie->tanggal }}">
                                @error('tgl_aktifitas') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="jam_mulai" class="float-right col-form-label">Jam Mulai</label></div>
                            <div class="col-sm-10">
                                <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ $activitie->jam_mulai }}">
                                @error('jam_mulai') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="jam_selesai" class="float-right col-form-label">Jam Selesai</label></div>
                            <div class="col-sm-10">
                                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ $activitie->jam_selesai }}">
                                @error('jam_selesai') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="keterangan" class="float-right col-form-label">Keterangan</label></div>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ $activitie->keterangan }}</textarea>
                                @error('keterangan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <img id="image" src="{{ $activitie->FotoUrl }}" alt="{{$activitie->FotoUrl}}" class="img-thumbnail mb-1">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="foto" class="float-right col-form-label">Foto</label></div>
                            <div class="col-sm-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto">
                                    <label class="custom-file-label" for="foto">Ubah Foto</label>
                                </div>
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
