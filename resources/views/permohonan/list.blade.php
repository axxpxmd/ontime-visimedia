@extends('layouts.app')

@section('title')
    Permohonan - {{ config('app.name') }}
@endsection

@section('header')
@endsection
@push('styles')
    {{-- <link href="{{ url('argon') }}/assets/js/plugins/datatable/bootstrap.css" rel="stylesheet" /> --}}
    <link href="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <style>
        .dataTables_filter {
            float: right;
        }

    </style>
@endpush

@section('content')

    <!-- Begin Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Permohonan </h5>
                        <a href="javascript:;" onclick="add(1)"  class="btn btn-primary btn-sm float-right" title="Tambah Permohonan"><i class="fas fa-plus"></i></a>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="permohonan">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Tanggal Dari</th>
                                        <th>Tanggal Sampai</th>
                                        <th>Keterangan Pemohon</th>
                                        <th>Keterangan Atasan</th>
                                        <th>Status</th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="ubahKehadiranLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">


                    <h5 class="modal-title" id="formTitle">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="needs-validation" id="form" method="POST" novalidate autocomplete="off" enctype="multipart/form-data">
                    @csrf @method('patch')
                    <div class="modal-body">
                        <div id="alert"></div>
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group row">
                            <label for="ubah_keterangan" class="col-form-label col-sm-3">Jenis</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="jenis"
                                    id="jenis" disabled>

                                    <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>Izin
                                    </option>
                                    <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected' : '' }}>Cuti
                                    </option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row" >
                            <label for="" class="col-form-label col-sm-3">Tanggal Dari</label>
                            <div class="col-sm-9">
                                <input type="text" class="datepicker form-control" name="tanggal_dari" id="tanggal_dari"
                                    class="form-control " disabled>

                            </div>
                        </div>
                        <div class="form-group row" >
                            <label for="" class="col-form-label col-sm-3">Tanggal Sampai</label>
                            <div class="col-sm-9">
                                <input type="text" class="datepicker form-control" name="tanggal_sampai" id="tanggal_sampai"
                                    class="form-control " disabled>

                            </div>
                        </div>
                        <div class="form-group row" >
                            <label for="" class="col-form-label col-sm-3">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan_pemohon" id="keterangan_pemohon" cols="30" rows="3" class="form-control" disabled></textarea>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><label for="foto" class="float-right col-form-label">Dokumen Pendukung</label></div>
                            <div class="col-sm-9">
                                <img src="" id="url_file" alt="" class="mb-2" width="250px" height="150px" style="display:none">

                            </div>
                        </div>

                        <hr>

                        <div class="form-group row" >
                            <label for="" class="col-form-label col-sm-3">Status</label>
                            <div class="col-sm-9">
                               <select name="status" id="status" class="form-control">
                                   <option value="2">Setujui</option>
                                   <option value="3">Tolak</option>
                               </select>
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label for="" class="col-form-label col-sm-3">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan_atasan" id="keterangan_atasan" cols="30" rows="3" class="form-control" ></textarea>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="justify-content: center !important;">
                        <button type="submit" class="btn btn-primary btn-sm" id="action"><i
                            class="fas fa-save mr-2"></i>Simpan<span id="txtAction"></span></button>
                        {{-- <button type="submit" class="btn btn-primary btn-sm" id="action"><i
                            class="fas fa-save mr-2"></i>Simpan<span id="txtAction"></span></button>
                    <a class="btn btn-sm" onclick="add()" id="reset">Reset</a> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')

    <script src="{{ url('argon') }}/assets/js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script>
        var dateToday = new Date();
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            minDate: dateToday,
            scrollMonth : false,
            scrollInput : false
        });

        var table = $('#permohonan').dataTable({
            language: {
                paginate: {
                    next: '›', // or '→'
                    previous: '‹' // or '←'
                }
            },
            responsive: true,
            pageLength: 10,
            processing: true,
            serverSide: true,

            ajax: {
                url: "{{ route('permohonan.api-list') }}",
                method: 'GET'
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    align: 'center',
                    className: 'text-center'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'tanggal_dari',
                    name: 'tanggal_dari'
                },
                {
                    data: 'tanggal_sampai',
                    name: 'tanggal_sampai'
                },
                {
                    data: 'keterangan_pemohon',
                    name: 'keterangan_pemohon'
                },
                {
                    data: 'keterangan_atasan',
                    name: 'keterangan_atasan'
                },
                {
                    data: 'status',
                    name: 'status'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });

        table.on('draw.dt', function() {
            var PageInfo = $('#permohonan').DataTable().page.info();
            table.api().column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        function add(t) {
            if(t ==1){

                $("#formModal").modal()
            }
            save_method = "add";
            $('#form').trigger('reset');
            $('#id').val('');
            $('#formTitle').html('Tambah Data');
            $('input[name=_method]').val('POST');
            $('#txtAction').html('');
            $('#reset').show();
            $('#kode').focus();
            $('#url_file').attr('src','');
                    $('#url_file').hide();
        }

        function edit(id) {
            save_method = 'edit';
            var id = id;
            $('#alert').html('');
            $('#form').trigger('reset');
            $('#formTitle').html(
                "Permohonan");
            $('#txtAction').html(" ");
            $('#reset').hide();
            $('input[name=_method]').val('PATCH');
            $.get("{{ route('permohonan.list-show', ':id') }}".replace(':id', id), function(data) {
                $('#id').val(data.id);
                $('#jenis').val(data.jenis);
                $('#keterangan_pemohon').val(data.keterangan_pemohon);
                $('#tanggal_dari').val(data.tanggal_dari);
                $('#tanggal_sampai').val(data.tanggal_sampai);
                if(data.file){
                    let s = '{{config("app.ftp_src")}}'
                    $('#url_file').attr('src',s+data.file);
                    $('#url_file').show();
                }else{
                    $('#url_file').attr('src','');
                    $('#url_file').hide();
                }
                $("#formModal").modal();

            }, "JSON").fail(function() {
                console.log("Nothing Data");
                reload();
            });
        }



        add();
        $('#form').on('submit', function(e) {
            if ($(this)[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                $('#alert').html('');
                $('#action').attr('disabled', true);;
                if (save_method == 'add') {
                    url = "{{ route('permohonan.store') }}";
                } else {
                    url = "{{ route('permohonan.list-update', ':id') }}".replace(':id', $('#id').val());
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: new FormData($(this)[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#action').removeAttr('disabled');
                        if (data.success == 1) {
                            // $('#alert').html(
                            //     "<div role='alert' class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Success!</strong> " +
                            //     data.message + "</div>");
                            table.api().ajax.reload();
                            if (save_method == 'add') {
                                add();
                            }
                            $("#formModal").modal('hide');
                        }
                    },
                    error: function(data) {
                        $('#action').removeAttr('disabled');
                        err = '';
                        respon = data.responseJSON;
                        $.each(respon.errors, function(index, value) {
                            err = err + "<li>" + value + "</li>";
                        });

                        $('#alert').html(
                            "<div role='alert' class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Error!</strong> " +
                            respon.message + "<ol class='pl-3 m-0'>" + err + "</ol></div>");
                    }
                });
                return false;
            }
            $(this).addClass('was-validated');
        });
    </script>
@endpush
