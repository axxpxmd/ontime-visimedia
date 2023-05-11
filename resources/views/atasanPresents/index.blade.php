@extends('layouts.app')

@section('title')
Kehadiran - {{ config('app.name') }}
@endsection
@push('styles')
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"
/>
@endpush

{{-- @section('header')
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
                </div>
            </div>
        </div>
    </div>
@endsection --}}
@section('header')
<div class="row ">
    <div class="col-md-12">
        <div class="row mb-3">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
                <div class="card card-stats mb-3 mb-xl-0 " >
                    <div class="card-body p-2 ">
                        <div class="text-center mb-2">
                            <span class="avatar  rounded-circle text-center" style="width:80px;height:80px">
                                <img alt="Image placeholder" src="{{ Auth::user()->getPhoto() }}">
                            </span>
                        </div>
                        <table class="table p-0 table-responsive" >
                            <tr>
                                <td style="width: 10%">Nama</td>
                                <td style="width: 2%">:</td>
                                <td>
                                    {{ auth()->user()->nama }}
                                </td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>
                                    {{ getUnitKerja(auth()->user()->id) ?? '-' }}
                                </td>
                            </tr>
                        </table>


                    </div>
                </div>
            </div>




        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="row">

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Jam Kerja</h5>
                                <span class="h5">  {{ isset($jamkerja->mulai_kerja) ? date_format(date_create($jamkerja->mulai_kerja),'H:i') .' - '. date_format(date_create($jamkerja->selesai_kerja),'H:i') : 'Libur' }} <br>
                                    Masuk : {{ $my ? $my->jam_masuk ? $my->jam_masuk:'-'  : '-'}}</span>
                                {{-- <span class="h2 font-weight-bold mb-0">{{ $masuk }}</span> --}}
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{$hari}}</h5>
                                <span class="h5" style="line-height: 200%;">  {{ $tgl }}
                                    <br>
                                    Keluar : {{ $my ? $my->jam_keluar  ? $my->jam_keluar :'-'  : '-'}}</span>
                                {{-- <span class="h2 font-weight-bold mb-0">{{ $masuk }}</span> --}}
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


    {{-- <div class="row align-items-center vh-20">
        <div class="col-3 mx-auto">
            <div class="container d-flex flex-column align-items-center ">
                <a href="{{route('home')}}?c=1" class="btn bg-gradient-green text-white"> <i class="fas fa-check"></i> Check In / Check Out</a>
            </div>

        </div>
    </div> --}}
@endsection

@section('content')

<!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Kehadiran ({{ $presents->total() }})</h5>
                {{-- <a href="{{ route('activities.create') }}" class="btn btn-primary btn-sm float-right" title="Tambah Aktifitas"><i class="fas fa-plus"></i></a> --}}
                {{-- <form class="float-right mr-1" action="{{ route('kehadiran.excel-users') }}" method="get">
                    <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                    <button class="btn btn-sm btn-primary" type="submit" title="Download"><i class="fas fa-download"></i></button>
                </form> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-1">

                        <form class="form-inline" action="{{ route('atasanPresents.search') }}" method="get">

                            <input type="date" class="form-control mb-2 mr-sm-2" id="tanggal" placeholder="" name="tanggal"  value="{{ request('tanggal', date('Y-m-d')) }}">

                            <label class="sr-only" for="keterangan">Keterangan</label>
                            <select class="form-control mb-2 mr-sm-2" name="keterangan" id="keterangan" onchange="">
                                <option value="">Semua</option>
                                @foreach ($keterangan as $item)
                                    <option value="{{ $item }}" {{ $sel_keterangan == $item ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>

                            <label class="sr-only" for="tanggal">Name</label>
                            <input type="text" class="form-control mb-2 mr-sm-2 col-lg-3" placeholder="Nama Pegawai " id="nama_pegawai" placeholder="" name="nama_pegawai"  value="{{ request('nama_pegawai','') }}">

                            <button type="submit" class="btn btn-primary mb-2">Cari</button>
                          </form>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$presents->count())
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data yang tersedia</td>
                                </tr>
                            @else
                                @foreach ($presents as $present)

                                        <tr>
                                            <th>{{ $rank++ }}</th>
                                            <td>{{ $present->tanggal }}</td>
                                            <td>{{ $present->user->nama }}</td>
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
                                                    <a data-fancybox data-src="{{ $present->fotoDatang() }}" >
                                                        <img src="{{ $present->fotoDatang() }}" width="50px" height="50px" />
                                                      </a>
                                                </td>
                                            @else
                                                <td>-</td>
                                            @endif
                                            @if($present->jam_keluar)
                                                <td>{{ date('H:i:s', strtotime($present->jam_keluar)) }}
                                                    <br>
                                                    <a data-fancybox data-src="{{ $present->fotoPulang() }}" >
                                                        <img src="{{ $present->fotoPulang() }}" width="50px" height="50px" />
                                                      </a>
                                                    </td>
                                            @else
                                                <td>-</td>
                                            @endif
                                            @if ($present->keterangan!="Alpha" && $present->keterangan!="Libur")
                                                {{-- <td><a href="#" class="btn btn-sm btn-info" title="Setujui Permohonan"><i class="fas fa-eye"></i></a></td> --}}
                                                <td><a href="{{ route('atasanPresents.edit', $present) }}" class="btn btn-sm btn-info" title="Tinjau Absen"><i class="fas fa-eye"></i></a></td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>

                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-left">
                    {{ $presents->appends($_GET)->links() }}
                </div>

            </div>
        </div>
    </div>
<!-- /.container-fluid -->

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endpush
