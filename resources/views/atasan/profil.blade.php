@extends('layouts.app')
@section('title')
Profil - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold">Profil</h5>
                </div>
                <div class="card-body">
                    <form action=" {{ route('update-profil', Auth::user()->id) }} " method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="text-center mb-3">
                            <img id="image" src="{{ Auth::user()->getPhoto()  }}" alt="{{ Auth::user()->foto }}" class="img-thumbnail mb-1">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="foto" class="col-form-label">Foto</label></div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto">
                                    <label class="custom-file-label" for="foto">Ubah Foto</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="username" class="col-form-label">Username</label></div>
                            <div class="col-sm-9">
                                <input disabled type="text" class="form-control" id="username" name="username" value="{{ Auth::user()->username }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="nama" class="col-form-label">Nama</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ Auth::user()->nama }}">
                                @error('nama') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="role" class="col-form-label ">Role</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('role') is-invalid @enderror" name="role" id="role">
                                    <option value="">Pilih</option>
                                    <option value="1" {{ old('role',Auth::user()->role_id) == 1 ? 'selected' : '' }}>Admin</option>
                                    <option value="2" {{ old('role',Auth::user()->role_id) == 2 ? 'selected' : '' }}>Atasan</option>
                                    <option value="3" {{ old('role',Auth::user()->role_id) == 3 ? 'selected' : '' }}>Pegawai</option>
                                </select>
                                @error('role') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="atasan" class="col-form-label">Nama Atasan</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('atasan') is-invalid @enderror" name="atasan" id="atasan">
                                    <option value="">Pilih</option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == Auth::user()->atasan_id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                @error('atasan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="uker" class="col-form-label ">Unit Kerja</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('uker') is-invalid @enderror" name="uker" id="uker">
                                    <option value="">Pilih</option>
                                    @foreach ($ukers as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == Auth::user()->uker_id ? 'selected' : '' }}>{{ $item->unit_kerja }}</option>
                                    @endforeach
                                </select>
                                @error('uker') <span class="invalid-feedback" uker="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary btn-block">
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
