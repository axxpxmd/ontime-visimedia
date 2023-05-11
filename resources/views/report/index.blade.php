@extends('layouts.app')

@section('title')
    Report - {{ config('app.name') }}
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
        <link href="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endpush


@section('content')

    <!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-1">

                        <form action="{{ route('report.excel') }}">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-sm-3"><label for="periode_mulai" class="float-right col-form-label">Periode Mulai</label></div>
                                        <div class="col-sm-9">
                                            <input type="text" name="periode_mulai" id="periode_mulai" onchange="table.DataTable().ajax.reload();" class="form-control mb-3 datepicker" value="{{ request('periode_mulai') ? request('periode_mulai') : date('Y-m-01') }}"  autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3"><label for="periode_selesai"   class="float-right col-form-label">Periode Selesai</label></div>
                                        <div class="col-sm-9">
                                            <input type="text" name="periode_selesai" id="periode_selesai" onchange="table.DataTable().ajax.reload();" class="form-control mb-3 datepicker" value="{{ request('periode_selesai') ? request('periode_selesai'):date('Y-m-t')}}"  autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-sm-3"><label for="opd" class="float-right col-form-label">OPD</label></div>
                                        <div class="col-sm-9">
                                            <select class="form-control mb-2 mr-sm-2" name="opd_id" id="opd_id" onchange="table.DataTable().ajax.reload();getUnitKerja()">
                                               @if (auth()->user()->role_id != 7)
                                               <option value="">Semua</option>
                                               @endif

                                                @foreach ($opds as $item)
                                                <option value="{{ $item->id }}"
                                                    >{{ $item->nama }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3"><label for="opd" class="float-right col-form-label">Unit Kerja</label></div>
                                        <div class="col-sm-9">
                                            <select class="form-control mb-2 mr-sm-2" name="unit_kerja_id" id="unit_kerja_id" onchange="table.DataTable().ajax.reload();getSubUnitKerja()">
                                                <option value="">Semua</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3"><label for="opd" class="float-right col-form-label">Sub Unit Kerja</label></div>
                                        <div class="col-sm-9">
                                            <select class="form-control mb-2 mr-sm-2" name="subunit_kerja_id" id="subunit_kerja_id" onchange="table.DataTable().ajax.reload();">
                                                <option value="">Semua</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>





                            <button title="Download" type="submit" style="float:right" class="btn btn-success mb-2">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button title="Cari" type="button" onclick="table.DataTable().ajax.reload();" style="float:right" class="btn btn-primary mb-2 mr-2">
                                <i class="fa fa-search"></i> Cari
                            </button>
                          </form>
                    </div>

                </div>
                <div class="table-responsive">
                <table class="table table-striped table-hover" id="tbl-pegawai">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>

                            <th>Unit Kerja</th>
                            <th>Jam Kerja</th>
                            <th>Sallary</th>
                            <th>Total Sanksi</th>
                            <th>Sallary Final</th>
                            <th>Detail</th>


                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            </div>

        </div>
    </div>
    <!-- /.container-fluid -->


@endsection


@push('scripts')
<script src="{{ url('argon') }}/assets/js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script type="text/javascript">
    $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth : false,
            scrollInput : false
        });
var table = $('#tbl-pegawai').dataTable({
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
                url: "{{ route('report.api') }}",
                method: 'GET',
                data: function ( d ) {

                      d.periode_mulai= $('#periode_mulai').val();
                    d.periode_selesai= $('#periode_selesai').val();
                    d.opd_id= $('#opd_id').val();
                    d.unit_kerja_id= $('#unit_kerja_id').val();
                    d.subunit_kerja_id= $('#subunit_kerja_id').val();
            }


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
                    data: 'uker',
                    name: 'uker'
                },
                {
                    data: 'jam_kerja',
                    name: 'jam_kerja'
                },
                {
                    data: 'sallary',
                    name: 'sallary'
                },
                {
                    data: 'denda',
                    name: 'denda'
                },
                {
                    data: 'yang_dibayarkan',
                    name: 'yang_dibayarkan'
                },
                {
                    data: 'detail_absen',
                    name: 'detail_absen'
                },


            ]
        });
        table.on('draw.dt', function() {
            var PageInfo = $('#tbl-pegawai').DataTable().page.info();
            table.api().column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });
        getUnitKerja();
        function getUnitKerja(){
        val = $('#opd_id').val();

        option = "<option value=''>Semua</option>";
        if(val == ""){
            $('#unit_kerja_id').html(option);
        }else{
            $('#unit_kerja_id').html("<option value=''>Loading...</option>");
            url = "{{ route('config.getUnit') }}";
            $.post(url,{ opd_id: val }, function(data){
                $.each(data, function( index, value ) {
                    option += "<option value='" + value.id + "'>" + value.nama +"</li>";
                });
                $('#unit_kerja_id').html(option);
                getSubUnitKerja();


            }, 'JSON');
        }
    }
    function getSubUnitKerja(){
        val = $('#unit_kerja_id').val();

        option = "<option value=''>Semua</option>";
        if(val == ""){
            $('#subunit_kerja_id').html(option);
        }else{
            $('#subunit_kerja_id').html("<option value=''>Loading...</option>");
            url = "{{ route('config.getSubUnit') }}";
            $.post(url,{ unit_kerja_id: val }, function(data){
                $.each(data, function( index, value ) {
                    option += "<option value='" + value.id + "'>" + value.nama +"</li>";
                });
                $('#subunit_kerja_id').html(option);



            }, 'JSON');
        }
    }
    </script>
@endpush
