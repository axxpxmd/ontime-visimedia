@extends('layouts.app')

@section('title')
Detail User - {{ config('app.name') }}
@endsection
@push('styles')
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"
/>
@endpush

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
                                    {{ getUnitKerja(auth()->user()->id) }}
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
                                <h5 class="card-title text-uppercase text-muted mb-0">{{$hari ?? ''}}</h5>
                                <span class="h5" style="line-height: 200%;">  {{ $tgl ?? '' }}
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

    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Kehadiran</h5>
                <a href="{{ route('daftar-hadir.pdf') }}" class="btn btn-sm btn-primary float-right" target="_blank"><i class="fas fa-download"></i></a>
            </div>
            <div class="card-body">
                <form action="{{ route('daftar-hadir.cari') }}" class="mb-3" method="get">
                    <div class="form-group row mb-3 ">
                        <label for="bulan" class="col-form-label col-sm-2">Bulan</label>
                        <div class="input-group col-sm-4">
                            <input type="month" class="form-control" name="bulan" id="bulan" value="{{ request('bulan',date('Y-m')) }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit">Cari</button>
                            </div>
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
                                <th>Total Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$presents->count())
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data yang tersedia</td>
                                </tr>
                            @else
                                @foreach ($presents as $present)

                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($present->tanggal)) }}</td>
                                        <td>
                                            {{ $present->keterangan }}
                                                @php
                                                $status = '';
                                                if($present->keterangan!="Alpha" && $present->keterangan!="Libur"){
                                                    if($present->status_permohonan == 1){
                                                        $status = " (Disetujui";
                                                    }elseif ($present->status_permohonan == 2) {
                                                        $status = " (Ditolak";
                                                    }else{
                                                        $status = ' (Belum ditinjau';

                                                    }
                                                    $status .= $present->keterangan_atasan ? " - $present->keterangan_atasan)":")";

                                                }



                                                @endphp
                                                   {{ $status }}
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
                                            <td>
                                                {{$present->total_jam ? $present->total_jam : '-'}}
                                            </td>
                                        @else
                                            <td>-</td>
                                            <td>-</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="float-right">
                        {{ $presents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endpush
