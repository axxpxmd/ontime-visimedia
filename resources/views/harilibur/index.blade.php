@extends('layouts.app')

@section('title')
    Hari Libur - {{ config('app.name') }}
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
            <div class="col-md-8">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Hari Libur </h5>
                        {{-- <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a> --}}
                    </div>
                    <div class="card-body">

                        <div class="">
                            <table class="table table-striped table-hover" id="hari-libur">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>

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
            <div class="col-md-4">
                <div class="card shadow ">
                    <div class="card-header">
                        <div id="alert"></div>
                        <h4 id="formTitle">Tambah Data</h4>

                        {{-- <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah User"><i class="fas fa-plus"></i></a> --}}
                    </div>
                    <div class="card-body">

                        <form class="needs-validation" id="form" method="POST" novalidate>
                            {{ method_field('POST') }}
                            <input type="hidden" id="id" name="id" />
                            @csrf

                            <div class="form-row form-inline">
                                <div class="col-md-12">
                                    <div class="form-group m-0 mb-1">
                                        <label for="nama" class="col-form-label s-12 col-md-4">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control">
                                    </div>
                                    <div class="form-group m-0">
                                        <label for="tgl " class="col-form-label s-12 col-md-4">Tanggal</label>
                                        <input type="text" name="tgl" id="tgl" class="form-control datepicker">
                                    </div>

                                    <div class="card-footer offset-md-3">
                                        <button type="submit" class="btn btn-primary btn-sm" id="action"><i
                                                class="fas fa-save mr-2"></i>Simpan<span id="txtAction"></span></button>
                                        <a class="btn btn-sm" onclick="add()" id="reset">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
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

        var table = $('#hari-libur').dataTable({
            language: {
                paginate: {
                    next: '›', // or '→'
                    previous: '‹' // or '←'
                }
            },
            pageLength: 10,
            processing: true,
            serverSide: true,

            ajax: {
                url: "{{ route('hari-libur.api') }}",
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
                    data: 'tgl',
                    name: 'tgl'
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
            var PageInfo = $('#hari-libur').DataTable().page.info();
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
            $.get("{{ route('hari-libur.edit', ':id') }}".replace(':id', id), function(data) {
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#tgl').val(data.tgl);

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
                                url: "{{ route('hari-libur.destroy', ':id') }}".replace(':id', id),
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
                                error: function() {
                                    console.log('Opssss...');
                                    reload();
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
                    url = "{{ route('hari-libur.store') }}";
                } else {
                    url = "{{ route('hari-libur.update', ':id') }}".replace(':id', $('#id').val());
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
