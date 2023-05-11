@extends('layouts.welcome')
@section('title')
    Home - {{ config('app.name') }}
@endsection
@section('content')
<div class="col d-flex justify-content-center mb-2">
    @if (auth()->user()->role->role == 'Admin')

        <a href="{{route('kehadiran.index')}}" class="btn bg-gradient-green text-white text-center"> <i class="fas fa-home"></i> Dashboard</a>
        @elseif (in_array(auth()->user()->role->role, ["Eselon 4","Eselon 3","Eselon 2","Eselon 1",]))
        <a href="{{route('atasanPresents.index')}}" class="btn bg-gradient-green text-white text-center"> <i class="fas fa-home"></i> Dashboard</a>

            @else
            <a href="{{route('daftar-hadir')}}" class="btn bg-gradient-green text-white text-center"> <i class="fas fa-home"></i> Dashboard</a>

@endif
</div>
    @if ($libur)
        <div class="text-center">
            <p>Absen Libur (Hari Libur Nasional {{ $holiday }})</p>
        </div>
    @else
        @if (date('l') == 'Saturday' || date('l') == 'Sunday')
            <div class="text-center">
                <p>Absen Libur</p>
            </div>
        @else
            {{-- Cek Lokasi Presensi dari Table User --}}
            @php
                $hitung = 1; // $hitung untuk menghitung berapa jumlah lokasi presensi yang dimiliki pegawai
            @endphp
            @foreach ($latlong as $item)
                @for ($i = 17; $i < $hasil; $i++)
                    @if ($item->id == $json[$i])
                        <input type="hidden" id="latitude{{ $hitung }}" value="{{ $item->latitude }}">
                        <input type="hidden" id="longitude{{ $hitung }}" value="{{ $item->longitude }}">
                        @php
                            $hitung = $hitung + 1;
                        @endphp
                    @endif
                @endfor
            @endforeach
            <input type="hidden" id="hitung" value="{{ $hitung - 1 }}">
            @if ($present)
                @if ($present->keterangan == 'Alpha')
                    <div class="text-center">
                        @if (strtotime(date('H:i:s')) >= strtotime($jamkerja->mulai_absen) && strtotime(date('H:i:s')) <= strtotime($jamkerja->selesai_kerja) && strtotime(date('H:i:s')) <= strtotime($jamkerja->maks_absen) )
                            <div id="map"></div>
                            <input type="hidden" id="distance">
                            <p id="pJarak">Silahkan Check-in</p>
                            <button id="btnJarak" type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#checkinModal" onclick="webCam()">Check-in</button>
                        @else
                            <p>Check-in Belum Tersedia</p>
                        @endif
                    </div>
                @elseif($present->keterangan == 'Cuti')
                    <div class="text-center">
                        <p>Status absen anda hari ini  " Cuti "</p>
                    </div>
                @elseif($present->keterangan == 'Sakit')
                <div class="text-center">
                    <p>Status absen anda hari ini  " Sakit "</p>
                </div>
                @elseif($present->keterangan == 'Izin')
                <div class="text-center">
                    <p>Status absen anda hari ini  " Izin "</p>
                </div>
                @else
                    <div class="text-center">
                        <p>
                            <!-- Elemen yang akan menjadi kontainer peta -->
                        <div id="map"></div>
                        <input type="hidden" id="distance">
                        <br>
                        Check-in hari ini pukul : ({{ $present->jam_masuk }})
                        </p>
                        @if ($present->jam_keluar)
                            <p>Check-out hari ini pukul : ({{ $present->jam_keluar }})</p>
                        @else
                            @if ($jamkerja->mulai_checkout <= Date('H:i:s'))
                                <p id="pJarak">Jika pekerjaan telah selesai silahkan check-out</p>
                                <button id="btnJarak" type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#checkoutModal" onclick="webCam()">
                                    Check-out
                                </button>
                            @else
                                <p>Check-out Belum Tersedia</p>
                            @endif
                        @endif
                    </div>

                    <!-- CheckOut Modal -->
                    <form action="{{ route('kehadiran.check-out', ['kehadiran' => $present]) }}" method="post">
                        @csrf @method('patch')
                        <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog"
                            aria-labelledby="checkoutModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="checkoutModalLabel">Pastikan foto terlihat dengan
                                            jelas</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                            onclick="webCamClose()">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="my_camera"></div>
                                        <div id="results"></div>
                                        <input type="hidden" name="image" class="image-tag">
                                        <input id="demo" type="hidden" name="lokasi">
                                    </div>
                                    <div class="modal-footer">
                                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="webCamClose()">Close</button> --}}
                                        <button type="button" id="snap" class="btn btn-secondary" onclick="takeSnap()">Ambil
                                            Foto</button>
                                        <button type="submit" id="simpan" class="btn btn-primary"
                                            onclick="webCamCloseSave()">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- End CheckOut Modal --}}
                @endif
            @else
                <div class="text-center">
                    @if (strtotime(date('H:i:s')) >= strtotime($jamkerja->mulai_absen) && strtotime(date('H:i:s')) <= strtotime($jamkerja->selesai_kerja))
                        <div id="map"></div>
                        <input type="hidden" id="distance">

                        {{-- End Lokasi Presensi --}}
                        <p id="pJarak">Silahkan Check-in </p>
                        <button id="btnJarak" type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#checkinModal" onclick="webCam()">Check-in</button>
                    @else
                        <p>Check-in Belum Tersedia</p>
                    @endif
                </div>
            @endif

            <!-- CheckIn Modal -->
            <form action="{{ route('kehadiran.check-in') }}" method="post">
                @csrf
                <div class="modal fade" id="checkinModal" tabindex="-1" role="dialog"
                    aria-labelledby="checkinModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="checkinModalLabel">Pastikan foto terlihat dengan jelas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    onclick="webCamClose()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="my_camera"></div>
                                <div id="results"></div>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="image" class="image-tag">
                                <input id="demo" type="hidden" name="lokasi">
                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="webCamClose()">Close</button> --}}
                                <button type="button" id="snap" class="btn btn-secondary" onclick="takeSnap()">Ambil
                                    Foto</button>
                                <button type="submit" id="simpan" class="btn btn-primary"
                                    onclick="webCamCloseSave()">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{-- End CheckIn Modal --}}
        @endif
    @endif
@endsection

@push('scripts')

    <script language="JavaScript">


        Webcam.on('error', function(err) {
            alert("Akses kamera tidak diizinkan pada browser anda!")
            document.getElementById("simpan").disabled = true;
            document.getElementById("snap").disabled = true;
        });

        function webCam() {
            jQuery('#my_camera').show();
            jQuery('#results').hide();
            document.getElementById("simpan").disabled = true;
            document.getElementById("snap").disabled = false;
            Webcam.set({
                width: 450,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_camera');
        }

        function webCamClose() {
            Webcam.reset('#my_camera');
            document.getElementById("simpan").disabled = true;
            document.getElementById("snap").disabled = false;
        }

        function takeSnap() {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                jQuery('#my_camera').hide();
                document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
                jQuery('#results').show();
            });
            document.getElementById("simpan").disabled = false;
            document.getElementById("snap").disabled = true;
        }
    </script>

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
            layers: [OpenStreetMap, jalan, googleHybrid, googleTraffic, googleStreets]
        })
        let area_checkin = {!! $area_checkin !!}

        // navigator.geolocation.getCurrentPosition((position) => {
        //     const p = position.coords;

        //     let marker = L.marker([p.latitude, p.longitude]).addTo(map);
        //     map.panTo(new L.LatLng(p.latitude, p.longitude));
        //     document.getElementById("demo").value = position.coords.latitude + "," + position.coords.longitude;
        //     area();
        // })
        getLocation();

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        var restrict = false;

        function showPosition(position) {
            const p = position.coords;
            document.getElementById("demo").value = position.coords.latitude + ", " + position.coords.longitude;
            let marker = L.marker([p.latitude, p.longitude]).addTo(map);

            map.panTo(new L.LatLng(p.latitude, p.longitude));
            $.each(area_checkin, function(i, v) {


                let dis = map.distance([p.latitude, p.longitude], [v.latitude, v.longitude]);
                if (restrict != true) {
                    if (dis <= 150) {
                        restrict = true;
                        console.log("re")
                    }
                }

            })
            if (!area_checkin.length) {
                restrict = true;
            }

            checkRestrict();
        }

        function checkRestrict() {
            if (!restrict) {
                $("#btnJarak").hide()
                $("#pJarak").html('Anda berada diluar jangkauan presensi');
            } else {
                $("#btnJarak").show()
                $("#pJarak").html('Silahkan Check-in');
                @if ($onclick)
                $('#btnJarak').trigger('click');
                @endif
            }
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Akses lokasi tidak diizinkan pada browser anda!");
                    $("#btnJarak").hide()
                    $("#pJarak").html('Akses lokasi tidak diizinkan pada browser anda!');
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }



        $.each(area_checkin, function(i, v) {
            let pos = $("#demo").val().split(',');
            let circle = L.circle([v.latitude, v.longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 150
            }).addTo(map);

            // let dis = L.map('distance').distance([pos[0], pos[1]], [v.latitude, v.longitude]);

        })


    </script>
@endpush
