@extends('layouts.app')
@section('title')
Tambah Unit Kerja - {{ config('app.name') }}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Tambah Unit Kerja</h5>
                    <a href="{{ route('unitkerjas.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('unitkerjas.store') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-4"><label for="unit_kerja" class="float-right col-form-label">Nama Unit Kerja</label></div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}">
                                @error('unit_kerja') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4"><label for="initial" class="float-right col-form-label">Initial</label></div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('initial') is-invalid @enderror" id="initial" name="initial" value="{{ old('initial') }}">
                                @error('initial') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-8">
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