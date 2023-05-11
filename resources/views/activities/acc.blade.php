@extends('layouts.app')
@section('title')
Tinjau Aktifitas - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Tinjau Aktifitas</h5>
                    <a href="{{ route('activities.index') }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('activities.update2', $activities->id) }} " method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="aktifitas" class="float-right col-form-label">Aktifitas Harian</label></div>
                            <div class="col-sm-10">
                                <input disabled type="text" class="form-control @error('aktifitas') is-invalid @enderror" id="aktifitas" name="aktifitas" value="{{ $activities->aktifitas }}">
                                @error('aktifitas') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="tgl_aktifitas" class="float-right col-form-label">Tanggal Aktifitas</label></div>
                            <div class="col-sm-10">
                                <input disabled type="date" class="form-control @error('tgl_aktifitas') is-invalid @enderror" id="tgl_aktifitas" name="tgl_aktifitas" value="{{ $activities->tanggal }}">
                                @error('tgl_aktifitas') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="jam_mulai" class="float-right col-form-label">Jam Mulai</label></div>
                            <div class="col-sm-10">
                                <input disabled type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ $activities->jam_mulai }}">
                                @error('jam_mulai') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="jam_selesai" class="float-right col-form-label">Jam Selesai</label></div>
                            <div class="col-sm-10">
                                <input disabled type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ $activities->jam_selesai }}">
                                @error('jam_selesai') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"><label for="keterangan" class="float-right col-form-label">Keterangan</label></div>
                            <div class="col-sm-10">
                                <textarea disabled class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ $activities->keterangan }}</textarea>
                                @error('keterangan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            @if ($activities->FotoUrl != '')

                            <img id="image" src="{{$activities->FotoUrl}}" alt="{{ $activities->fotoUrl }}" class="img-thumbnail mb-1">
                            @endif
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-6">
                                <input type="submit" name="setujui" class="btn btn-success btn-block" value="Setujui Aktifitas">
                            </div>
                            <div class="col-sm-6">
                                <input type="submit" name="tolak" class="btn btn-danger btn-block" value="Tolak Aktifitas">
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
