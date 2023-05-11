@extends('layouts.app')

@section('title')
    Kehadiran - {{ config('app.name') }}
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
@endpush

@section('header')
    <div class="row">
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Masuk</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $masuk }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Telat</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $telat }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-yellow text-white rounded-circle shadow">
                                <i class="fas fa-business-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Izin</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $izin }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-purple text-white rounded-circle shadow">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sakit</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $sakit }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-pink text-white rounded-circle shadow">
                                <i class="fas fa-ambulance"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Cuti</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $cuti }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Alpha</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $alpha }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@section('content')

    <!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left mx-2">Kehadiran</h5>

                {{-- <form class="float-right" action="{{ route('kehadiran.excel-users') }}" method="get">
                    <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                    <button class="btn btn-sm btn-primary" type="submit" title="Download"><i class="fas fa-download"></i></button>
                </form> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-1">
                        <form action="{{ route('kehadiran.search') }}" method="get">
                            <div class="form-group row">
                                <label for="tanggal" class="col-form-label col-sm-3">Tanggal</label>
                                <div class="input-group col-sm-9">
                                    <input type="date" class="form-control" name="tanggal" id="tanggal"
                                        value="{{ request('tanggal', date('Y-m-d')) }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6">

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Total Jam</th>
                                @if (auth()->user()->role->role == 'Admin')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$presents->count())
                                <tr>
                                    <td colspan="{{ auth()->user()->role->role == 'Admin' ? '8' : '7' }}"
                                        class="text-center">Tidak ada data yang tersedia</td>
                                </tr>
                            @else
                                @foreach ($presents as $present)
                                    <tr>
                                        <th>{{ $rank++ }}</th>
                                        <td><a
                                                href="{{ route('users.show', $present->user) }}">{{ $present->user->username }}</a>
                                        </td>
                                        <td>{{ $present->user->nama }}</td>
                                        <td>{{ $present->keterangan }}</td>
                                        @if ($present->jam_masuk)
                                            <td>{{ date('H:i:s', strtotime($present->jam_masuk)) }}
                                                <br>
                                                <a data-fancybox data-src="{{ $present->fotoDatang() }}">
                                                    <img src="{{ $present->fotoDatang() }}" width="50px" height="50px" />
                                                </a>

                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        @if ($present->jam_keluar)
                                            <td>{{ date('H:i:s', strtotime($present->jam_keluar)) }}
                                                <br>
                                                <a data-fancybox data-src="{{ $present->fotoPulang() }}">
                                                    <img src="{{ $present->fotoPulang() }}" width="50px" height="50px" />
                                                </a>
                                            </td>
                                            <td>
                                                {{$present->total_jam ? $present->total_jam : '-'}}
                                            </td>
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                        @endif
                                        @if (auth()->user()->role->role == 'Admin')
                                            <td>
                                                <button id="" data-id="{{ $present->id }}" type="button"
                                                    class="btn btn-sm btn-success btnUbahKehadiran" data-toggle="modal"
                                                    data-target="#ubahKehadiran">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                @if ($present->lokasi_datang || $present->lokasi_pulang)
                                                    <button id="" data-id="{{ $present->id }}" type="button"
                                                        class="btn btn-sm btn-info btnLokasi" data-toggle="modal"
                                                        data-target="#lokasi">
                                                        <i class="far fa-map"></i>
                                                    </button>
                                                @endif

                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    {{ $presents->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <!-- Modal -->
    <div class="modal fade" id="ubahKehadiran" tabindex="-1" role="dialog" aria-labelledby="ubahKehadiranLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahKehadiranLabel">Ubah Kehadiran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formUbahKehadiran" action="" method="post">
                    @csrf @method('patch')
                    <div class="modal-body">
                        <h5 class="mb-3" id="tanggal"></h5>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <div class="form-group row">
                            <label for="ubah_keterangan" class="col-form-label col-sm-3">Keterangan</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                    id="ubah_keterangan">
                                    <option value="Alpha" {{ old('keterangan') == 'Alpha' ? 'selected' : '' }}>Alpha
                                    </option>
                                    <option value="Masuk" {{ old('keterangan') == 'Masuk' ? 'selected' : '' }}>Masuk
                                    </option>
                                    <option value="Telat" {{ old('keterangan') == 'Telat' ? 'selected' : '' }}>Telat
                                    </option>
                                    <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>Izin
                                    </option>
                                    <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected' : '' }}>Cuti
                                    </option>
                                </select>
                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="jamMasuk">
                            <label for="ubah_jam_masuk" class="col-form-label col-sm-3">Jam Masuk</label>
                            <div class="col-sm-9">
                                <input type="time" name="jam_masuk" id="ubah_jam_masuk"
                                    class="form-control @error('jam_masuk') is-invalid @enderror">
                                @error('jam_masuk')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="jamKeluar">
                            <label for="ubah_jam_keluar" class="col-form-label col-sm-3">Jam Keluar</label>
                            <div class="col-sm-9">
                                <input type="time" name="jam_keluar" id="ubah_jam_keluar"
                                    class="form-control @error('jam_keluar') is-invalid @enderror">
                                @error('jam_keluar')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lokasi" tabindex="-1" role="dialog" aria-labelledby="ubahKehadiranLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahKehadiranLabel">Lokasi Absen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row lok_datang" style="display: none">

                    <div class="col-md-12 p-4">
                        <center><h4>Lokasi Datang</h4></center>
                        <div id="map" style="width: 100%;height:300px"></div>
                    </div>
                </div>
                <hr>

                <div class="row lok_pulang" style="display: none">

                    <div class="col-md-12 p-4">
                        <center><h4>Lokasi Pulang</h4></center>
                        <div id="map2" style="width: 100%;height:300px"></div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script type="text/javascript">
        function remove(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
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
                            $.post("{{ route('kehadiran.destroy', ':id') }}".replace(':id', id), {
                                '_method': 'DELETE',
                                '_token': csrf_token
                            }, function(data) {

                                location.reload();
                            }, "JSON").fail(function() {
                                location.reload();
                            });
                        }
                    },
                    cancel: function() {}
                }
            });
        }

        $('#ubah_keterangan').on('change', function() {
            let val = $('#ubah_keterangan').val();
            if (val == 'Masuk' || val == 'Telat') {
                $('#ubah_jam_masuk').prop('required', true);
                $('#ubah_jam_keluar').prop('required', true);
            } else {
                $('#ubah_jam_masuk').prop('required', false);
                $('#ubah_jam_keluar').prop('required', false);

                $('#ubah_jam_masuk').val('');
                $('#ubah_jam_keluar').val('');
            }
        });

        $('.btnUbahKehadiran').on('click', function() {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const id = $(this).data('id');
            $('#formUbahKehadiran').attr('action', "{{ url('kehadiran') }}/" + id);
            $.ajax({
                url: "{{ route('ajax.get.kehadiran') }}",
                method: 'post',
                dataType: 'json',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data) {
                    var date = new Date(data.tanggal);
                    var tahun = date.getFullYear();
                    var bulan = date.getMonth();
                    var tanggal = date.getDate();
                    var hari = date.getDay();
                    var jam = date.getHours();
                    var menit = date.getMinutes();
                    var detik = date.getSeconds();
                    $('#user_id').val(data.user_id);
                    switch (hari) {
                        case 0:
                            hari = "Minggu";
                            break;
                        case 1:
                            hari = "Senin";
                            break;
                        case 2:
                            hari = "Selasa";
                            break;
                        case 3:
                            hari = "Rabu";
                            break;
                        case 4:
                            hari = "Kamis";
                            break;
                        case 5:
                            hari = "Jum'at";
                            break;
                        case 6:
                            hari = "Sabtu";
                            break;
                    }
                    switch (bulan) {
                        case 0:
                            bulan = "Januari";
                            break;
                        case 1:
                            bulan = "Februari";
                            break;
                        case 2:
                            bulan = "Maret";
                            break;
                        case 3:
                            bulan = "April";
                            break;
                        case 4:
                            bulan = "Mei";
                            break;
                        case 5:
                            bulan = "Juni";
                            break;
                        case 6:
                            bulan = "Juli";
                            break;
                        case 7:
                            bulan = "Agustus";
                            break;
                        case 8:
                            bulan = "September";
                            break;
                        case 9:
                            bulan = "Oktober";
                            break;
                        case 10:
                            bulan = "November";
                            break;
                        case 11:
                            bulan = "Desember";
                            break;
                    }
                    $('#tanggal').html(hari + ", " + tanggal + " " + bulan + " " + tahun);
                    $('#ubah_keterangan').val(data.keterangan);
                    $('#ubah_jam_masuk').val(data.jam_masuk);
                    $('#ubah_jam_keluar').val(data.jam_keluar);
                }
            });

        });

        OpenStreetMap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
        OpenStreetMap2 = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");



        var pc = true;
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            pc = false;
        }

        $('.btnLokasi').on('click', function() {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const id = $(this).data('id');

            $.ajax({
                url: "{{ route('ajax.get.kehadiran') }}",
                method: 'post',
                dataType: 'json',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                success: function(data) {
                    $('.lok_pulang').hide();
                    $('.lok_datang').hide();
                    if (data.lokasi_datang) {
                        $('.lok_datang').show();
                        var map = L.map("map", {
                            center: [-6.291100, 106.715421],
                            zoom: 15,
                            //dragging: 1,
                            dragging: pc,
                            tap: pc,
                            pixelRatio: window.devicePixelRatio || 1,
                            fullscreenControl: true,
                            fullscreenControlOptions: {
                                position: "topleft"
                            },
                            measureControl: false,
                            layers: [OpenStreetMap
                            ]
                        })

                        latlng = data.lokasi_datang.split(', ');
                        let marker = L.marker([latlng[0], latlng[1]]).addTo(map);
                        map.panTo(new L.LatLng(latlng[0], latlng[1]));
                    }
                    if (data.lokasi_pulang) {
                        $('.lok_pulang').show();
                        var map2 = L.map("map2", {
                            center: [-6.291100, 106.715421],
                            zoom: 15,
                            //dragging: 1,
                            dragging: pc,
                            tap: pc,
                            pixelRatio: window.devicePixelRatio || 1,
                            fullscreenControl: true,
                            fullscreenControlOptions: {
                                position: "topleft"
                            },
                            measureControl: false,
                            layers: [OpenStreetMap2
                            ]
                        })

                        latlng = data.lokasi_pulang.split(', ');
                        let marker = L.marker([latlng[0], latlng[1]]).addTo(map2);
                        map2.panTo(new L.LatLng(latlng[0], latlng[1]));
                    }

                }
            });

        });
    </script>
@endpush
