@extends('layouts.app')
@section('title')
    Dashboard - {{ config('app.name') }}
@endsection
@push('styles')
    <style>
        @media (min-width: 90%) {
            .container {
                max-width: 100% !important;
            }
            h3 {
                font-weight: 0 !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <link href="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('header')
    <div class="row justify-content-center mb-2">
        <div class="col-xl-4 col-lg-6">
            <select name="opd_id" id="opd_id" class="form-control select2" style="font-size: 12px !important" onchange="Filter()">
                @if (auth()->user()->role_id != 7)
                    <option value="">Semua</option>
                @endif
                @foreach ($opds as $item)
                    <option value="{{ $item->id }}" @if ($item->id == $sel_opd) selected @endif>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-4 col-lg-6">
            <select name="bulan" id="bulan" class="form-control form-control-lg select2" onchange="Filter()">
                @foreach ($bulan as $k => $v)
                    <option value="{{ $k + 1 }}" @if ($k + 1 == $sel_bulan) selected @endif>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Pagu</h5>
                            <span class="h3  mb-0">{{ rupiah($pagu) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="fas fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sanksi Denda</h5>
                            <span class="h3  mb-0">{{ rupiah($sanksi_denda) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                <i class="fas fa-money-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Realisasi Sallary</h5>
                            <span class="h3  mb-0">{{ rupiah($realisasi) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Chart Efisiensi {{ $bulan[(int) $sel_bulan - 1] }}</h2>
                    </div>
                    <div class="card-body">
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Chart Efisiensi {{ date('Y') }}</h2>
                    </div>
                    <div class="card-body">
                        <div id="chart2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-12">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">List Sanksi Pegawai {{ $bulan[(int) $sel_bulan - 1] }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <table class="table table-striped table-hover" id="tbl-pegawai">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Unit Kerja</th>
                                        <th>Jam Kerja</th>
                                        <th>Sanksi</th>
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
@endsection
@push('scripts')
    <script script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script src="{{ url('argon') }}/assets/js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script>
        $(".select2").select2({
            width: 'resolve',
            height: 'resolve'
        });

        var table = $('#tbl-pegawai').dataTable({
            language: {
                paginate: {
                    next: '›', // or '?'
                    previous: '‹' // or '?'
                }
            },
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dashboard.api') }}",
                method: 'GET',
                data: {
                    bulan: $('#bulan').val(),
                    opd_id: $('#opd_id').val()
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
                    data: 'foto',
                    name: 'foto'
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
                    data: 'denda',
                    name: 'denda'
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

        Highcharts.chart('chart2', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Rupiah'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>Rp. {point.y:,.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    cursor: 'categories',
                    events: {
                        click: function(event) {
                            alert(
                                this.name + ' clicked\n' +
                                this.categories + event.altKey + '\n' +
                                'Control: ' + event.ctrlKey + '\n' +
                                'Meta: ' + event.metaKey + '\n' +
                                'Shift: ' + event.shiftKey
                            );
                        }
                    }
                }
            },
            series: {!! $chart2 !!},
            colors: ['#2ecc71', '#3498db', '#9b59b6', '#c0392b', '#f39c12']
        });

        Highcharts.chart('chart1', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: {!! $chart1 !!}
            }],
            colors: ['#12CBC4', '#ED4C67', '#009432', '#c0392b', '#f39c12']
        });

        function Filter() {
            window.location.href = "/dashboard?bulan=" + $('#bulan').val() + "&opd_id=" + $('#opd_id').val();
        }
    </script>
@endpush
