@extends('layouts.app')
@section('title')
Ajukan Permohonan - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Ajukan Permohonan</h5>
                    <a href="{{ route('daftar-hadir') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('ajukan-permohonan') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="tgl" value="{{ $presents->tanggal }}">
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="tanggal" class="float-right col-form-label">Tanggal</label></div>
                            <div class="col-sm-9">
                                <input disabled type="text" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ $presents->tanggal }}">
                                @error('tanggal') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="keterangan" class="float-right col-form-label ">Keterangan</label></div>
                            <div class="col-sm-9">
                                <select class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" id="keterangan">
                                    <option value="">Pilih</option>
                                    <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected':'' }}>Izin</option>
                                    <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected':'' }}>Sakit</option>
                                    <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected':'' }}>Cuti</option>
                                </select>
                                @error('keterangan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="foto" class="float-right col-form-label">Dokumen Pendukung</label></div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto">
                                    <label class="custom-file-label" for="foto">Upload Foto Dokumen</label>
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
    jQuery('#atasan').hide();
    jQuery('#lAtasan').hide();
    function select_role() {
		if (jQuery('#role').val() == 3) { // ROLE PEGAWAI
			jQuery('#atasan').show();
			jQuery('#lAtasan').show();
		}
		if (jQuery('#role').val() == 2) { // ROLE ATASAN
			jQuery('#atasan').hide();
			jQuery('#lAtasan').hide();
		}
		if (jQuery('#role').val() == 1) { // ROLE ADMIN
			jQuery('#atasan').hide();
			jQuery('#lAtasan').hide();
		}
	}

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