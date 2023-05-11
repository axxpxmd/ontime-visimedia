@extends('layouts.app')
@section('title')
    Pengumuman - {{ config('app.name') }}
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Pengumuman</h5>

                </div>
                <div class="card-body">
                    <form action=" {{ route('config.pengumuman_store') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        @if (isset($config->path))
                        <div class="text-center mb-3">
                            <img id="image" src="{{ config('app.ftp_src') . $config->path }}"
                                alt="{{ config('app.ftp_src') . $config->path }}" width="300px" height="200px"
                                class="img-thumbnail mb-1">
                        </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-sm-3"><label for="Pengumuman" class="float-right col-form-label">Pengumuman</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="pengumuman" name="pengumuman">
                                    <label class="custom-file-label" for="Pengumuman">Ubah</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="status" class="float-right col-form-label">Status</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="status" id="status" class="form-control">
                                    <option value="0" @if($config->status == 0) selected @endif>Tidak Aktif</option>
                                    <option value="1" @if($config->status != 0) selected @endif>Aktif</option>
                                </select>
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
