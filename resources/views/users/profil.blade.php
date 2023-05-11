@extends('layouts.app')
@section('title')
    Profil - {{ config('app.name') }}
@endsection
@section('content')
<div class="row mb-3 mt--9">
    <div class="col-xl-2 col-lg-7">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Masuk</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $masuk }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-7">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Telat</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $telat }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-yellow text-white rounded-circle shadow">
                        <i class="fas fa-business-time"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-7">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Izin</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $izin }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-purple text-white rounded-circle shadow">
                        <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-7">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Sakit</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $sakit }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-pink text-white rounded-circle shadow">
                        <i class="fas fa-ambulance"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-7">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Cuti</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $cuti }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-7">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Alpha</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $alpha }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold">Profil</h5>
                </div>
                <div class="card-body">
                    <form action=" {{ route('update-profil', Auth::user()->id) }} " method="post"
                        enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="text-center mb-3">
                            <img id="image" src="{{ Auth::user()->getPhoto() }}" width="300px" height="200px"
                                alt="{{ Auth::user()->foto }}" class="img-thumbnail mb-1">
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
                                <input disabled type="text" class="form-control" id="username" name="username"
                                    value="{{ Auth::user()->username }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="nama" class="col-form-label">Nama</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ Auth::user()->nama }}">
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="role" class="col-form-label ">Role</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('role') is-invalid @enderror" name="role"
                                    id="role">
                                    <option value="">Pilih</option>
                                    <option value="1" {{ old('role', Auth::user()->role_id) == 1 ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="2" {{ old('role', Auth::user()->role_id) == 2 ? 'selected' : '' }}>
                                        Eselon 4</option>
                                    <option value="4" {{ old('role', Auth::user()->role_id) == 4 ? 'selected' : '' }}>
                                        Eselon 4</option>
                                    <option value="5" {{ old('role', Auth::user()->role_id) == 5 ? 'selected' : '' }}>
                                        Eselon 4</option>
                                    <option value="6" {{ old('role', Auth::user()->role_id) == 6 ? 'selected' : '' }}>
                                        Eselon 4</option>
                                    <option value="3" {{ old('role', Auth::user()->role_id) == 3 ? 'selected' : '' }}>
                                        Pegawai</option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="atasan" class="col-form-label">Nama Atasan</label></div>
                            <div class="col-sm-9">
                                <select class="form-control @error('atasan') is-invalid @enderror" name="atasan" id="atasan" disabled>
                                    <option value="">Pilih</option>

                                </select>
                                @error('atasan')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="uker" class="col-form-label ">Unit Kerja</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('uker') is-invalid @enderror" name="uker"
                                    id="uker">
                                    <option value="">Pilih</option>
                                    @foreach ($ukers as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == Auth::user()->uker_id ? 'selected' : '' }}>
                                            {{ $item->unit_kerja }}</option>
                                    @endforeach
                                </select>
                                @error('uker')
                                    <span class="invalid-feedback" uker="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="sallary" class="col-form-label">Sallary</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control " id="sallary"
                                    name="sallary" value="{{ Auth::user()->sallary }}" disabled>

                            </div>
                        </div>
                        @if (Auth::user()->getSk())
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="sk" class="col-form-label">SK</label></div>
                            <div class="col-sm-9" style="padding-top:11px">
                                <a href="{{ Auth::user()->getSk() }}">Document</a>

                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-sm-3"><label for="shift" class=" col-form-label">Shift</label></div>
                            <div class="col-sm-9">
                                <select disabled class="form-control @error('shift_id') is-invalid @enderror" name="shift_id" id="shift_id"
                                onchange="">
                                <option value="">Pilih</option>
                                @foreach ($shifts as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == Auth::user()->shift_id ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shift_id')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
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
        $('document').ready(function() {
            getAtasan({{  Auth::user()->atasan_id}})
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });

        function getAtasan(atasan = null){
        val = $('#role').val();
        val2 = $('#uker').val();
        option = "<option value=''>Pilih</option>";
        if(val == "" || val == "" ){
            $('#atasan').html(option);
        }else{
            $('#atasan').html("<option value=''>Loading...</option>");
            url = "{{ route('user.getAtasan') }}";
            $.get(url,{ role: val, uker: val2 }, function(data){
                $.each(data, function( index, value ) {
                    option += "<option value='" + value.id + "'>" + value.nama +"</li>";
                });
                $('#atasan').html(option);
                if(atasan != null){
                    $('#atasan').val(atasan);
                }


            }, 'JSON');
        }
    }
    </script>
@endpush
