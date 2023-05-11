@extends('layouts.app')
@section('title')
    Config App - {{ config('app.name') }}
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Config App</h5>

                </div>
                <div class="card-body">
                    <form action=" {{ route('config.store') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center mb-3">
                            <img id="image" src="{{ config('app.ftp_src') . $config->icon }}"
                                alt="{{ config('app.ftp_src') . $config->icon }}" width="300px" height="200px"
                                class="img-thumbnail mb-1">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="icon" class="float-right col-form-label">icon</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="icon" name="icon">
                                    <label class="custom-file-label" for="icon">Ubah icon</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="app_id" class="float-right col-form-label">APP
                                    ID</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('app_id') is-invalid @enderror" id="app_id"
                                    name="app_id" value="{{ old('app_id') ?? $config->app_id }}">
                                @error('app_id')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="nama" class="float-right col-form-label">Nama Aplikasi
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ old('nama') ?? $config->nama }}">
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="pemilik" class="float-right col-form-label">Nama
                                    Pemilik
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('pemilik') is-invalid @enderror" id="pemilik"
                                    name="pemilik" value="{{ old('pemilik') ?? $config->pemilik }}">
                                @error('pemilik')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
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
        $('document').ready(function() {
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
    </script>
@endpush
