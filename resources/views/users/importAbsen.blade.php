@extends('layouts.app')
@section('title')
    Import Absen - {{ config('app.name') }}
@endsection
@push('styles')
<style>
.form-control:disabled{
    background-color: #ffffff !important;
}
.select2-container--bootstrap.select2-container--disabled .select2-selection{
        background-color: #ffffff !important;
}
</style>


@endpush
@section('content')


    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold">Import Absen</h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-6 col-md-12">
                            <div id="alertSc"></div>
    <form class="needs-validation" id="form5" method="POST" novalidate>

        {{ method_field('POST') }}
        <input type="hidden" name="id" value="{{ $id }}">
        <div class="form-row form-inline">
            <div class="col-md-7">
                <div class="form-group mb-1">
                    <label for="fileExcel" class="col-form-label s-12 col-md-4"><strong>Nama</strong></label>
                    <input type="text"  value="{{ $user->nama }}" disabled class="form-control r-0 light s-12 col-md-7" autocomplete="off" required/>

                </div>
                <div class="form-group m-0">
                    <label for="fileExcel" class="col-form-label s-12 col-md-4"><strong>File Excel</strong></label>
                    <input type="file" name="fileExcel" id="fileExcel" aria-describedby="fileExcelHelp" placeholder="" class="form-control r-0 light s-12 col-md-7" autocomplete="off" required/>
                    <small id="fileExcelHelp" class="form-text text-muted offset-md-4">Unduh <a href="{{ asset('file/example-presensi.xlsx') }}" title="Unduh template excel">template excel</a>.</small>
                </div>
            </div>
            <div class="col-md-5">
                <strong>Tahapan Penyimpanan Data Excel</strong>
                <ol class="pl-4">
                    <li>Validasi Data Excel <span id="step1Presensi"></span></li>
                    <li>Menyimpan Data <span id="step2Presensi"></span></li>
                </ol>
            </div>
            <div class="card-body offset-md-3">
                <button type="submit" class="btn btn-primary btn-sm" id="action5"><i class="icon-save mr-2"></i>Unggah</button>
            </div>
        </div>
    </form>

                        </div>

                        <div class="col-lg-6">
                            <div id="alert5"></div>
                        </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#form5').on('submit', function (e) {
        if ($(this)[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        else{
            $('#alert5').html('');
            $('#action5').attr('disabled', true);
            $('#step1Presensi').html("<img src='{{ asset('img/flickrLoader.gif') }}' alt=''/></span>");
            form = new FormData($(this)[0]);
            $.ajax({
                url: "{{ route('users.importAbsenStoreStep1') }}",
                type: 'POST',
                data: form,
                dataType:'JSON',
                contentType: false,
                processData: false,
                success : function(data) {
                    $('#step1Presensi').html("<i class='ni ni-check-bold text-success'></i>");
                    exportStorePresensi(form);
                },
                error : function(data){
                    $('#action5').removeAttr('disabled');
                    err = '';
                    respon = data.responseJSON;
                    if(respon.errors){
                        $.each(respon.errors, function( index, value ) {
                            err = err + "<li>" + value +"</li>";
                        });
                        message = respon.message;
                    }else{
                        message = data.statusText;
                    }
                    $('#step1Presensi').html("<i class='ni ni-fat-remove text-danger'></i>");
                    $('#alert5').html("<div role='alert' class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Error!</strong> " + message + "<ol class='pl-3 m-0'>" + err + "</ol></div>");
                }
            });
            return false;
        }
        $(this).addClass('was-validated');
    });


    function exportStorePresensi(form){
        $('#step2Presensi').html("<img src='{{ asset('img/flickrLoader.gif') }}' alt=''/></span>");
        $.ajax({
            url: "{{ route('users.importAbsenStoreStep2') }}",
            type: 'POST',
            data: form,
            dataType:'JSON',
            contentType: false,
            processData: false,
            success : function(data2) {
                $('#action5').removeAttr('disabled');
                $('#step2Presensi').html("<i class='ni ni-check-bold text-success'></i>");
                $('#alertSc').html("<div role='alert' class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Success!</strong> " + data2.message + "</div>");
                // table.api().ajax.reload();
            },
            error : function(data2){
                $('#action5').removeAttr('disabled');
                err = '';
                respon = data2.responseJSON;
                if(respon.errors){
                    $.each(respon.errors, function( index, value ) {
                        err = err + "<li>" + value +"</li>";
                    });
                    message = respon.message;
                }else{
                    message = data2.statusText;
                }
                $('#step2Presensi').html("<i class='ni ni-fat-remove text-danger'></i>");
                $('#alert5').html("<div role='alert' class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Error!</strong> " + message + "<ol class='pl-3 m-0'>" + err + "</ol></div>");
            }
        });
    }

    </script>
@endpush
