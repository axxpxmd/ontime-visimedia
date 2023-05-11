@extends('layouts.app')
@section('title')
    Dinas - {{ config('app.name') }}
@endsection
@section('header')
@endsection
@push('styles')
    <link href="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <style>
        .dataTables_filter {
            float: right;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Dinas </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table display nowrap table-striped table-bordered" style="width:100%" id="satker">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Logo</th>
                                        <th>Unit Kerja</th>
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
            @if (auth()->user()->role_id ==1)
            <div class="col-md-4">
                <div class="card shadow ">
                    <div class="card-header">
                        <div id="alert"></div>
                        <h4 id="formTitle">Tambah Data</h4>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" id="form" method="POST" novalidate>
                            {{ method_field('POST') }}
                            <input type="hidden" id="id" name="id" />
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        <label for="nama" class="col-form-label col-md-4">Nama <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="nama" id="nama" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="alamat" class="col-form-label col-md-4">Alamat <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="alamat" id="alamat" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="logo" class="col-form-label col-md-4">Logo <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="file" name="logo" id="logo" class="form-control" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="keterangan" class="col-form-label col-md-4">Keterangan</label>
                                        <div class="col-sm-8">
                                            <textarea type="file" name="keterangan" id="keterangan" class="form-control" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="keterangan" class="col-form-label col-md-4"></label>
                                        <div class="col-sm-8">
                                            <button type="submit" class="btn btn-primary btn-sm" id="action"><i class="fas fa-save mr-2"></i>Simpan<span id="txtAction"></span></button>
                                            <a class="btn btn-sm" onclick="add()" id="reset">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ url('argon') }}/assets/js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script>
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth : false,
            scrollInput : false
        });

        var table = $('#satker').dataTable({
            language: {
                paginate: {
                    next: '›', // or '→'
                    previous: '‹' // or '←'
                }
            },
            scrollX: true,
            pageLength: 10,
            processing: true,
            serverSide: true,

            ajax: {
                url: "{{ route('satker.api') }}",
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
                    data: 'logo',
                    name: 'logo'
                },
                {
                    data: 'unit_kerja',
                    name: 'unit_kerja'
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
            var PageInfo = $('#satker').DataTable().page.info();
            table.api().column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        function add() {
            save_method = "add";
            $('#form').trigger('reset');
            $('#formTitle').html('Tambah Data');
            $('input[name=_method]').val('POST');
            $('#txtAction').html('');
            $('#reset').show();
            $('#kode').focus();
        }

        function edit(id) {
            save_method = 'edit';
            var id = id;
            $('#alert').html('');
            $('#form').trigger('reset');
            $('#formTitle').html(
                "Edit Data <a href='#' onclick='add()' class='btn btn-outline-primary btn-sm pull-right'>Batal</a>");
            $('#txtAction').html(" Perubahan");
            $('#reset').hide();
            $('input[name=_method]').val('PATCH');
            $.get("{{ route('satker.edit', ':id') }}".replace(':id', id), function(data) {
                $('#logo').removeAttr('required');
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#keterangan').val(data.keterangan);


            }, "JSON").fail(function() {
                console.log("Nothing Data");
                reload();
            });
        }

        function remove(id) {
            $.confirm({
                title: '',
                content: 'Apakah Anda yakin akan menghapus data ini?',
                icon: 'icon icon-question amber-text',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    ok: {
                        text: "ok!",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function() {
                            $.ajax({
                                url: "{{ route('satker.destroy', ':id') }}".replace(':id', id),
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': '{{ csrf_token() }}',
                                },
                                success: function(data) {
                                    table.api().ajax.reload();
                                    if (id == $('#id').val()) {
                                        add();
                                    }
                                },
                                error: function(data) {
                                    console.log('Opssss...');
                                    alert(data.responseJSON.message)
                                }
                            });
                        }
                    },
                    cancel: function() {
                        console.log('the user clicked cancel');
                    }
                }
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
                    url = "{{ route('satker.store') }}";
                } else {
                    url = "{{ route('satker.update', ':id') }}".replace(':id', $('#id').val());
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
                            $('#alert').html(
                                "<div role='alert' class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Success!</strong> " +
                                data.message + "</div>");
                            table.api().ajax.reload();
                            if (save_method == 'add') {
                                add();
                            }
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
