@extends('layouts.app')

@section('title')
Shift Create  - {{ config('app.name') }}
@endsection

@section('header')

@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Shift Create </h5>
                <a href="{{ route('jamkerja.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>

            </div>
            <div class="card-body">
                <form action=" {{ route('shift.store') }} " method="post" enctype="multipart/form-data">
                    @method('post')
                    @csrf


                    <div class="form-group row">
                        <div class="col-sm-3"><label for="name" class="float-right col-form-label">Name</label></div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="" >
                            @error('name') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                        </div>
                    </div>



                    <div class="form-group row justify-content-end">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-success btn-block">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
<!-- /.container-fluid -->

@endsection

@push('scripts')
<script>

$('.timepicker').datetimepicker({
    datepicker:false,
  format:'H:i:s',
  scrollMonth : false,
            scrollInput : false
});
</script>
@endpush
