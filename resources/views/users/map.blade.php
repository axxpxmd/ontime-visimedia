@extends('layouts.app')

@section('title')
    Lokasi Pegawai - {{ config('app.name') }}
@endsection

@push('styles')
    <style>
        #map {
            height: 600px;
        }

    </style>
    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
@endpush

@section('content')
    <!-- Begin Page Content -->
    <div class="container">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="m-0 pt-1 font-weight-bold float-left">Lokasi Pegawai</h5>
                <div class="text-right"><button class="btn btn-info btn-sm">Refresh</button></div>
            </div>
            <div class="card-body">
                <l-map style="height: 300px" :zoom="zoom" :center="center">
                    <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
                    <l-marker :lat-lng="markerLatLng"></l-marker>
                </l-map>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection


@push('scripts')
    <script>
        OpenStreetMap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");

        googleHybrid = L.tileLayer("https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}", {
                maxZoom: 20,
                subdomains: ["mt0", "mt1", "mt2", "mt3"]
            }),

            googleTraffic = L.tileLayer("https://{s}.google.com/vt/lyrs=m@221097413,traffic&x={x}&y={y}&z={z}", {
                maxZoom: 20,
                minZoom: 2,
                subdomains: ["mt0", "mt1", "mt2", "mt3"]
            });

        // //HitamPutih = L.tileLayer("https://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png"),

        googleStreets = L.tileLayer("https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
            maxZoom: 20,
            minZoom: 2,
            subdomains: ["mt0", "mt1", "mt2", "mt3"]
        });

        jalan = L.tileLayer(
            "https://1.base.maps.api.here.com/maptile/2.1/maptile/newest/normal.day/{z}/{x}/{y}/256/png8?pois=true&lg=ind&app_id=1g1pBeObAdqzorA7Avdd&app_code=3IsuKQ82__s_-kgjjiCRCw"
        );

        var pc = true;
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            pc = false;
        }

        var map = L.map("map", {
            center: [-6.291100, 106.715421],
            zoom: 12,
            //dragging: 1,
            dragging: pc,
            tap: pc,
            pixelRatio: window.devicePixelRatio || 1,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: "topleft"
            },
            measureControl: false,
            layers: [OpenStreetMap, jalan, googleHybrid, googleTraffic, googleStreets]
        })
    </script>
@endpush
