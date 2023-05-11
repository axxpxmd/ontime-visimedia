<!--

=========================================================
* Argon Dashboard - v1.1.2
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2020 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        @yield('title')
    </title>
    <!-- Favicon -->
    @php
        $conf = App\ConfigApp::first();
    @endphp
    <link href="{{ config('app.ftp_src') . $conf->icon }}" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ url('argon') }}/assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="{{ url('argon') }}/assets/js/plugins/datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="{{ url('argon') }}/assets/js/plugins/select2/select2.min.css" rel="stylesheet" /> --}}

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <link href="{{ url('argon') }}/assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- CSS Files -->
    <link href="{{ url('argon') }}/assets/css/argon-dashboard.css?v=1.1.2" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
    @stack('styles')

</head>

<body class="">
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
        <div class="container-fluid">
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Brand -->
            <a class="navbar-brand pt-0" href="{{ route('home') }}">
                <h1>PRESENSI</h1>
            </a>
            <!-- User -->
            <ul class="nav align-items-center d-md-none">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="{{ Auth::user()->getPhoto() }}"
                                    alt="{{ Auth::user()->foto }}">
                            </span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="dropdown-item">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Collapse header -->
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="{{ route('home') }}">
                                <h1>{{ config('app.name') }}</h1>
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                                aria-label="Toggle sidenav">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Navigation -->
                <ul class="navbar-nav">
                    @if (auth()->user()->role->role == 'Admin')
                    <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                        <a class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }} "
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-home text-primary"></i>Dashboard
                        </a>
                    </li>
                        @if (Request::segment(1) == 'kehadiran')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('kehadiran.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kehadiran.index') }}">
                        @endif
                        <i class="ni ni-check-bold text-purple"></i> Presensi
                        </a>
                        </li>

                        @if (Request::segment(1) == 'users')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('users.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">
                        @endif
                        <i class="ni ni-circle-08 text-orange"></i> Pegawai
                        </a>
                        </li>


                        <li class="nav-item {{ Request::segment(2) == 'maps' ? 'active' : '' }}">
                            <a class="nav-link {{ Request::segment(2) == 'maps' ? 'active' : '' }} "
                                href="/s/users/maps">
                                <i class="ni ni-map-big text-info"></i>Lokasi Pegawai
                            </a>
                        </li>

                        <li class="nav-item {{ Request::segment(1) == 'report' ? 'active' : '' }}">
                            <a class="nav-link {{ Request::segment(1) == 'report' ? 'active' : '' }} "
                                href="{{ route('report.index') }}">
                                <i class="fas fa-print text-pink"></i>Report
                            </a>
                        </li>
                    @elseif (in_array(auth()->user()->role->role, ["Eselon 4","Eselon 3","Eselon 2","Eselon 1",]))
                        @if (Request::segment(1) == 'atasanPresents')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('atasanPresents.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('atasanPresents.index') }}">
                        @endif
                        <i class="ni ni-check-bold text-primary"></i> Presensi
                        </a>
                        </li>

                        @if (Request::segment(1) == 'activities')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('activities.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('activities.index') }}">
                        @endif
                        <i class="ni ni-active-40 text-red"></i> Aktifitas
                        </a>
                        </li>

                        @if (Request::segment(1) == 'atasan')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('atasan.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('atasan.index') }}">
                        @endif
                        <i class="ni ni-circle-08 text-orange"></i> Pegawai
                        </a>
                        </li>
                        <li class="nav-item {{ Request::segment(2) == 'maps' ? 'active' : '' }}">
                            <a class="nav-link {{ Request::segment(2) == 'maps' ? 'active' : '' }} "
                                href="/s/users/maps">
                                <i class="ni ni-map-big text-info"></i>Lokasi Pegawai
                            </a>
                        </li>
                        <li class="nav-item {{ Request::segment(1) == 'permohonan' ? 'active' : '' }}">
                            <a class="nav-link {{ Request::segment(1) == 'permohonan' ? 'active' : '' }} "
                                href="{{ route('permohonan.list') }}">
                                <i class="ni ni-calendar-grid-58 text-green"></i> Permohonan
                            </a>
                        </li>
                    @elseif (in_array(auth()->user()->role->role, ["Pegawai"]))
                        @if (Request::segment(1) == 'daftar-hadir')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('daftar-hadir') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('daftar-hadir') }}">
                        @endif
                        <i class="ni ni-check-bold text-primary"></i> Presensi
                        </a>
                        </li>

                        @if (Request::segment(1) == 'activities')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('activities.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('activities.index') }}">
                        @endif
                        <i class="ni ni-active-40 text-info"></i> Aktifitas
                        </a>
                        </li>

                        <li class="nav-item {{ Request::segment(1) == 'permohonan' ? 'active' : '' }}">
                            <a class="nav-link {{ Request::segment(1) == 'permohonan' ? 'active' : '' }} "
                                href="{{ route('permohonan.index') }}">
                                <i class="ni ni-calendar-grid-58 text-red"></i> Permohonan
                            </a>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role->role, ["Admin OPD"]))
                    <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                        <a class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }} "
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-home text-primary"></i>Dashboard
                        </a>
                    </li>
                    @if (Request::segment(1) == 'kehadiran')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('kehadiran.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kehadiran.index') }}">
                        @endif
                        <i class="ni ni-check-bold text-purple"></i> Presensi
                        </a>
                        </li>
                         @if (Request::segment(1) == 'users')
                            <li class="nav-item active">
                                <a class="nav-link active" href="{{ route('users.index') }}">
                                @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">
                        @endif
                        <i class="ni ni-circle-08 text-orange"></i> Pegawai
                        </a>
                        </li>
                        <li class="nav-item {{ Request::segment(1) == 'report' ? 'active' : '' }}">
                            <a class="nav-link {{ Request::segment(1) == 'report' ? 'active' : '' }} "
                                href="{{ route('report.index') }}">
                                <i class="fas fa-print text-pink"></i>Report
                            </a>
                        </li>
                    @endif


                    @if (Request::segment(1) == 'profil')
                        <li class="nav-item active">
                            <a class="nav-link active" href="{{ route('profil') }}">
                            @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profil') }}">
                    @endif
                    <i class="ni ni-single-02 text-yellow"></i> Profil
                    </a>
                    </li>


                </ul>
                @if (in_array(auth()->user()->role->role,['Admin','Admin OPD']) )
                    <hr class="my-3">
                    <h6 class="navbar-heading text-muted">Konfigurasi</h6>
                    <ul class="navbar-nav">
                    @if (Request::segment(1) == 'satker')
                    <li class="nav-item active">
                        <a class="nav-link active" href="{{ route('satker.index') }}">
                        @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('satker.index') }}">
                @endif
                <i class="ni ni-building text-green"></i> Unit Kerja
                </a>
                </li>

                @if (Request::segment(1) == 'locations')
                    <li class="nav-item active">
                        <a class="nav-link active" href="{{ route('locations.index') }}">
                        @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('locations.index') }}">
                @endif
                <i class="ni ni-pin-3 text-grey"></i> Lokasi Presensi
                </a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'jam-kerja' ? 'active' : '' }}">
                    <a class="nav-link {{ Request::segment(1) == 'jam-kerja' ? 'active' : '' }} "
                        href="{{ route('jamkerja.index') }}">
                        <i class="ni ni-time-alarm text-red"></i> Jam Kerja
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'sanksi' ? 'active' : '' }}">
                    <a class="nav-link {{ Request::segment(1) == 'sanksi' ? 'active' : '' }} "
                        href="{{ route('tmsanksi.index') }}">
                        <i class="ni ni-books text-blue"></i> Sanksi
                    </a>
                </li>
                @if (in_array(auth()->user()->role->role,['Admin']) )

                <li class="nav-item {{ Request::segment(1) == 'hari-libur' ? 'active' : '' }}">
                    <a class="nav-link {{ Request::segment(1) == 'hari-libur' ? 'active' : '' }} "
                        href="{{ route('hari-libur.index') }}">
                        <i class="ni ni-calendar-grid-58 text-green"></i> Hari Libur
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(2) == 'pengumuman' ? 'active' : '' }}">
                    <a class="nav-link {{ Request::segment(2) == 'pengumuman' ? 'active' : '' }} "
                        href="{{ route('config.pengumuman') }}">
                        <i class="ni ni-books text-blue"></i>Pengumuman
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'config' ? 'active' : '' }}">
                    <a class="nav-link {{ Request::segment(1) == 'config' ? 'active' : '' }} "
                        href="{{ route('config.index') }}">
                        <i class="ni ni-settings-gear-65 text-black"></i>Konfigurasi App
                    </a>
                </li>
                @endif
                 </ul>
                @endif
                <hr class="my-3">
                <ul class="navbar-nav">
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}?c=1">
                            <i class="ni ni-check-bold text-info"></i> Check In / Check Out
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ni ni-user-run text-info"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
                    href="{{ route('home') }}">Home</a>
                <!-- User -->
                <ul class="navbar-nav align-items-center d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image placeholder" src="{{ Auth::user()->getPhoto() }}"
                                        alt="{{ Auth::user()->foto }}">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->nama }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item">
                                <i class="ni ni-user-run"></i>
                                <span>Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End Navbar -->

        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">


                <div class="header-body">
                    <!-- Card stats -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show"><button type="button"
                                class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('header')
                </div>
            </div>
        </div>
        <div class="container-fluid mt--7">
            @yield('content')
            <!-- Footer -->
            <footer class="footer">
                <div class="row align-items-center justify-content-xl-between">
                    <div class="col-xl-6">
                        <div class="copyright text-center text-xl-left text-muted">
                            <a href="{{ config('app.url') }}" class="font-weight-bold ml-1"
                                target="_blank">{{ config('app.name') }}</a> © {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core   -->
    <script src="{{ url('argon') }}/assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('argon') }}/assets/js/plugins/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    {{-- <script src="{{ url('argon') }}/assets/js/plugins/select2/select2.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="{{ url('argon') }}/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!--   Optional JS   -->
    <script>
        window.TrackJS &&
            TrackJS.install({
                token: "ee6fab19c5a04ac1a32a645abde4613a",
                application: "argon-dashboard-free"
            });
            $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    </script>
    @stack('scripts')

    <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>


</body>

</html>
