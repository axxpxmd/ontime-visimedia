@extends('layouts.welcome')
@section('title')
    Home - {{ config('app.name') }}
@endsection
@section('content')
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
                        @if (strtotime(date('H:i:s')) >= strtotime($jamkerja->mulai_absen) && strtotime(date('H:i:s')) <= strtotime($jamkerja->selesai_kerja))
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
                        <p>Anda Sedang Cuti</p>
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
                            @if (\Carbon\Carbon::parse($present->jam_masuk)->diffInHours(\Carbon\Carbon::parse(date('H:i:s'))) >= 0)
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
        navigator.geolocation.getCurrentPosition(showPosition);
        let lat, long, latDB, longDB, jarak;

        function showPosition(position) {
            lat = position.coords.latitude;
            long = position.coords.longitude;
            document.getElementById("demo").value = position.coords.latitude + ", " + position.coords.longitude;

            // MENGHITUNG JARAK UNTUK PRESENSI, PRESENSI DILAKUKAN MAKSIMAL 150 METER DARI KANTOR
            let hitung = document.getElementById("hitung").value;
            for (let i = 1; i <= hitung; i++) {
                latDB = document.getElementById("latitude" + i).value;
                longDB = document.getElementById("longitude" + i).value;
                jarak = document.getElementById("distance").value = L.map('distance').distance([lat, long], [latDB,
                longDB]);
                if (jarak > 150) {
                    document.getElementById("btnJarak").disabled = true;
                    document.getElementById("pJarak").innerHTML = 'Anda berada diluar jangkauan presensi';
                    break;
                } else {
                    break;
                }
            }

            let map = L.map('map').setView([lat, long], 17);
            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: '<a href="https://diskominfo.tangerangselatankota.go.id/">{{config('app.name')}}</a> Kota Tangerang Selatan',
                maxZoom: 18,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoicHJlc2Vuc2lrb21pbmZvIiwiYSI6ImNrejd2Y2txaTBuOGwycXFtdW56ZWVobnYifQ.vzuq9gaJtjvb2FvIQ-_hGA'
            }).addTo(map);

            let marker = L.marker([lat, long]).addTo(map);
            let circle = L.circle([latDB, longDB], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 150
            }).addTo(map);
        }
    </script>
@endpush
