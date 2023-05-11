@extends('layouts.app')
@section('title')
Tambah Pegawai - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Tambah Pegawai</h5>
                    <a href="{{ route('atasan.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('users.store') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center mb-3">
                            <img id="image" src="{{ asset('argon/default.png') }}" alt="{{ asset('argon/default.png') }}" width="300px" height="200px" class="img-thumbnail mb-1">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="foto" class="float-right col-form-label">Foto</label></div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto">
                                    <label class="custom-file-label" for="foto">Ubah Foto</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="nama" class="float-right col-form-label">Nama</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}">
                                @error('nama') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="username" class="float-right col-form-label">Username</label></div>
                            <div class="col-sm-9">
                                <input type="text" onkeypress="return hanyaAngka(event)" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}">
                                @error('username') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="role" class="float-right col-form-label">Role</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('role') is-invalid @enderror" name="role" id="role" onchange="select_role()">
                                    <option value="">Pilih</option>
                                    <option value="3" selected>Pegawai</option>
                                </select>
                                @error('role') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="atasan" class="float-right col-form-label " id="lAtasan">Nama Atasan</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('atasan') is-invalid @enderror" name="atasan" id="atasan">
                                    <option value="">Pilih</option>
                                    <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->nama }}</option>
                                </select>
                                @error('atasan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="uker" class="float-right col-form-label ">Unit Kerja</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('uker') is-invalid @enderror" name="uker" id="uker">
                                    <option value="">Pilih</option>
                                    @foreach ($ukers as $item)
                                        <option value="{{ Auth::user()->uker_id }}" {{ Auth::user()->uker_id == $item->uker_id ? 'selected' : '' }}>{{ $item->unit_kerja }}</option>
                                    @endforeach
                                </select>
                                @error('uker') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
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
    // jQuery('#atasan').hide();
    // jQuery('#lAtasan').hide();
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
