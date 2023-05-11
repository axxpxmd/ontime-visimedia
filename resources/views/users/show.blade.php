@extends('layouts.app')

@section('title')
    Detail User - {{ config('app.name') }}
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <link href="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endpush
@section('header')
    <div class="row">
        <div class="col-xl-3 col-lg-6">
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
                    <p class="mt-3 mb-0 text-muted text-sm">
                        Total Keterangan Masuk
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
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
                    <p class="mt-3 mb-0 text-muted text-sm">
                        Total Telat {{ $totalJamTelat }} Jam bulan Ini
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
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
                    <p class="mt-3 mb-0 text-muted text-sm">
                        Total Keterangan Cuti
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
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
                    <p class="mt-3 mb-0 text-muted text-sm">
                        Total Keterangan Alpha
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-3">
                <div class="card shadow ">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Detail User</h5>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary float-right">Kembali</a>
                    </div>
                    <div class="card-body">
                        <center><img src="{{ $user->foto }}" width="300px" height="200px" class=" mb-3"
                            alt="{{ $user->foto }}">
                        </center>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td width="100px">Username</td>
                                        <td>: {{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>: {{ $user->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>Role</td>
                                        <td>: {{ $user->role->role }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sallary</td>
                                        <td>: {{ $user->sallary ? rupiah($user->sallary) : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Sanksi Absen</td>
                                        <td>: {{ $sanksi ? rupiah(intval($sanksi)) : '-'  }}</td>
                                    </tr>
                                    <tr>
                                        <td>Final Sallary </td>
                                        <td>: {{  $user->sallary ?  rupiah($user->sallary -  intval($sanksi)) :  '-' }} </td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>: {{ $user->s_akun == 1 ? 'Aktif' : 'Tidak Aktif'  }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="float-right">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-success" title="Ubah"><i
                                        class="fas fa-edit"></i></a>
                                @if ($user->id != auth()->user()->id && in_array(auth()->user()->role->id,[1,7]))

                                    <form class="d-inline-block" action="{{ route('users.destroy', $user) }}"
                                        method="post">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus user ini ???')"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                                <form class="d-inline-block" action="{{ route('users.password', $user) }}" method="post">
                                    @csrf @method('patch')
                                    <button type="submit" class="btn btn-sm btn-dark"
                                        onclick="return confirm('Apakah anda yakin ingin mereset password user ini ???')">Reset
                                        Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold float-left">Kehadiran</h5>
                        @if ($libur == false)
                            @if (date('l') != 'Saturday' && date('l') != 'Sunday')
                            @if (auth()->user()->role->id == '1')
                                <button title="Tambah Kehadiran" type="button" class="btn btn-sm btn-primary float-right"
                                    data-toggle="modal" data-target="#kehadiran">
                                    <i class="fas fa-plus"></i>
                                </button>
                                @endif
                            @endif
                        @endif
                        <form class="float-right d-inline-block" action="{{ route('kehadiran.excel-user', $user) }}"
                            method="get">
                            @if (auth()->user()->role->id == '1')
                        <a href="{{ route('users.importAbsen',$user->id) }}" title="Import Presensi" class="btn btn-sm btn-info mr-2"> <i class="fas fa-upload"></i></a>
                            @endif
                        {{-- <input type="hidden" name="bulan" value="{{ request('bulan', date('Y-m')) }}"> --}}
                            <input type="hidden" name="periode_mulai" value="{{ request('periode_mulai') ? request('periode_mulai') : date('Y-m-01') }}">
                            <input type="hidden" name="periode_selesai" value="{{ request('periode_selesai') ? request('periode_selesai') : date('Y-m-t') }}">
                            <button title="Download Absen" type="submit" class="btn btn-sm btn-success mr-2">
                                <i class="fas fa-download"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kehadiran.cari', ['user' => $user]) }}" class="mb-3"
                            method="get">
                            {{-- <div class="form-group row mb-3 ">
                                <label for="bulan" class="col-form-label col-sm-2">Bulan</label>
                                <div class="input-group col-sm-10">
                                    <input type="month" class="form-control" name="bulan" id="bulan"
                                        value="{{ request('bulan', date('Y-m')) }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-row align-items-center">
                                <div class="col-sm-5 my-1">
                                    <label for="bulan" class="col-form-label ">Periode Mulai</label>
                                  <input type="text" name="periode_mulai" id="periode_mulai" class="form-control mb-3 datepicker" value="{{ request('periode_mulai') ? request('periode_mulai') : date('Y-m-01') }}"  autocomplete="off">
                                </div>
                                <div class="col-sm-5 my-1">
                                    <label for="bulan" class="col-form-label ">Periode Selesai</label>
                                  <input type="text" name="periode_selesai" id="periode_selesai" class="form-control mb-3 datepicker" value="{{ request('periode_selesai') ? request('periode_selesai'):date('Y-m-t')}}"  autocomplete="off">
                                </div>

                                <div class="col-auto my-1">
                                  <button type="submit" class="btn btn-primary mb-3" style="margin-top:40px">Cari</button>
                                </div>
                              </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Jam Kerja</th>
                                        <th>Diluar Jam Kerja</th>
                                        <th>Potongan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$presents->count())
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data yang tersedia</td>
                                        </tr>
                                    @else
                                        @foreach ($presents as $present)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($present->tanggal)) }}</td>
                                                <td>{{ $present->keterangan }}
                                                    
                                                    @php
                                                    $status = '';
                                                    if($present->keterangan!="Alpha" && $present->keterangan!="Libur"){
                                                        if($present->status_permohonan == 1){
                                                            $status = " (<span class='text-success'>Disetujui</span>";
                                                        }elseif ($present->status_permohonan == 2) {
                                                            $status = " (<span class='text-danger'>Ditolak/<span>";
                                                        }else{
                                                            $status = ' (<span style="color: #FFD600">Belum ditinjau</span>';
    
                                                        }
                                                        $status .= $present->keterangan_atasan ? " - $present->keterangan_atasan)":")";
    
                                                    }
    
    
    
                                                    @endphp
                                                       {!!$status !!}
                                                </td>
                                                @if ($present->jam_masuk)
                                                    <td>{{ date('H:i:s', strtotime($present->jam_masuk)) }}
                                                        <br>
                                                        <a data-fancybox data-src="{{ $present->fotoDatang() }}">
                                                            <img src="{{ $present->fotoDatang() }}" width="50px"
                                                                height="50px" />
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                @if ($present->jam_keluar)
                                                    <td>{{ date('H:i:s', strtotime($present->jam_keluar)) }}
                                                        <br>
                                                        <a data-fancybox data-src="{{ $present->fotoPulang() }}">
                                                            <img src="{{ $present->fotoPulang() }}" width="50px"
                                                                height="50px" />
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$present->total_jam ? $present->total_jam : '-'}}
                                                    </td>
                                                    <td>
                                                        {{$present->total_lembur ? $present->total_lembur : '-'}}
                                                    </td>

                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif
                                                <td>
                                                    {{$present->denda ? rupiah($present->denda) : '-'}}
                                                </td>
                                                <td>
                                                    <button id="btnUbahKehadiran" data-id="{{ $present->id }}"
                                                        type="button" class="btn btn-sm btn-success btnUbahKehadiran" data-toggle="modal"
                                                        data-target="#ubahKehadiran">
                                                        <i class="far fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="float-right">
                                {{ $presents->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="m-0 pt-1 font-weight-bold">Riwayat</h5>
                    </div>
                    <div class="card-body">
                        <div class="nav-wrapper">
                            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link mb-sm-2 mb-md-0 active" id="riwayat-kerja-tab" data-toggle="tab" href="#riwayat-kerja" role="tab" aria-controls="riwayat-kerja" aria-selected="false">Riwayat Kerja</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-2 mb-md-0" id="riwayat-pendidikan-tab" data-toggle="tab" href="#riwayat-pendidikan" role="tab" aria-controls="riwayat-pendidikan" aria-selected="false">Riwayat Pendidikan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-2 mb-md-0" id="riwayat-pelatihan-tab" data-toggle="tab" href="#riwayat-pelatihan" role="tab" aria-controls="riwayat-pelatihan" aria-selected="false">Seminar / Workshop / Diklat /Pelatihan</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">


                                    {{-- Riwayat Kerja --}}
                                    <div class="tab-pane fade show active" id="riwayat-kerja" role="tabpanel" aria-labelledby="riwayat-kerja-tab">
                                        @include('users.partials.riwayat_kerja')


                                    </div>

                                  {{-- Riwayat Pendidikan --}}
                                    <div class="tab-pane fade" id="riwayat-pendidikan" role="tabpanel" aria-labelledby="riwayat-pendidikan-tab">
                                        @include('users.partials.riwayat_pendidikan')
                                    </div>

                                  {{-- Riwayat Seminar --}}
                                    <div class="tab-pane fade" id="riwayat-pelatihan" role="tabpanel" aria-labelledby="riwayat-pelatihan-tab">
                                        @include('users.partials.riwayat_pelatihan')
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="kehadiran" tabindex="-1" role="dialog" aria-labelledby="kehadiranLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kehadiranLabel">Tambah Kehadiran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kehadiran.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <h5 class="mb-3">{{ date('l, d F Y') }}</h5>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group row">
                            <label for="keterangan" class="col-form-label col-sm-3">Keterangan</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                    id="keterangan">
                                    <option value="Alpha" {{ old('keterangan') == 'Alpha' ? 'selected' : '' }}>Alpha
                                    </option>
                                    <option value="Masuk" {{ old('keterangan') == 'Masuk' ? 'selected' : '' }}>Masuk
                                    </option>
                                    <option value="Telat" {{ old('keterangan') == 'Telat' ? 'selected' : '' }}>Telat
                                    </option>
                                    <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="jamMasuk">
                            <label for="jam_masuk" class="col-form-label col-sm-3">Jam Masuk</label>
                            <div class="col-sm-9">
                                <input type="time" name="jam_masuk" id="jam_masuk"
                                    class="form-control @error('jam_masuk') is-invalid @enderror">
                                @error('jam_masuk')
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
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
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
                                    <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="Cuti" {{ old('keterangan') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="jamMasuk">
                            <label for="ubah_jam_masuk" class="col-form-label col-sm-3">Jam Masuk</label>
                            <div class="col-sm-9">
                                <input type="time" step="1" name="jam_masuk" id="ubah_jam_masuk"
                                    class="form-control @error('jam_masuk') is-invalid @enderror">
                                @error('jam_masuk')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="jamKeluar">
                            <label for="ubah_jam_keluar" class="col-form-label col-sm-3">Jam Keluar</label>
                            <div class="col-sm-9">
                                <input type="time" step="1"  name="jam_keluar" id="ubah_jam_keluar"
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
@endsection

@push('scripts')
<script src="{{ url('argon') }}/assets/js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script>
        var table_riwayat_kerja,table_riwayat_pelatihan,table_riwayat_pendidikan;
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',

            scrollMonth: false,
            scrollInput: false
        });
        $('.select2').select2({
                    theme: "bootstrap"
                });
        $(document).ready(function() {

            $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            scrollMonth : false,
            scrollInput : false
        });
            $('#jamMasuk').hide();
            $('#keterangan').on('change', function() {
                if ($(this).val() == 'Masuk' || $(this).val() == 'Telat') {
                    $('#jamMasuk').show();
                } else {
                    $('#jamMasuk').hide();
                }
            });


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
            function removeRiwayat(id,type) {
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
                                url: "{{ route('profil.remove_riwayat', ':id') }}".replace(':id', id),
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': '{{ csrf_token() }}',
                                    'type': type,
                                },
                                success: function(data) {
                                   if(type == 1){
                                    table_riwayat_kerja.columns.adjust().draw();
                                   }
                                   if(type == 3){
                                    table_riwayat_pelatihan.columns.adjust().draw();
                                   }
                                   if(type == 2){
                                    table_riwayat_pendidikan.columns.adjust().draw();
                                   }
                                    // if (id == $('#id').val()) {
                                    //     add();
                                    // }
                                    $.alert({type:'green',title:'',content:data.message});
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
    </script>
@endpush
