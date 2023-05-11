@php
    $conf = App\ConfigApp::first();
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - PRESENSI</title>
    <link href="{{ config('app.ftp_src') . $conf->icon }}" rel="icon" type="image/png">

    <!-- LINEARICONS -->
    <link rel="stylesheet" href="{{ asset('assetLogin/fonts/linearicons/style.css') }}">

    <!-- STYLE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assetLogin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assetLogin/css/util.css') }}">

</head>
<body>
    <div class="wrapper">
        <div class="inner">
            <img src="{{ asset('assetLogin/images/gif.gif') }}" alt="" class="image-1" style="margin-left: -120px !important; margin-bottom: 33px !important">
            <form method="POST" action="{{ route('login') }}" class="needs-validation" style="border-radius: 20px" novalidate>
                @csrf
                <div class="text-center" style="margin-top: -40px !important;">
                    <img src="https://dataawan.tangerangselatankota.go.id/presensi/file-config/1654833732.png" class="img-fluid mb-2" width="110" alt="">
                    <p class="fw-bold fs-25 text-white">Selamat Datang</p>
                    <p class="text-white mt-2">Sistem Presensi Pegawai</p>
                </div>
                <div class="mt-4">
                    <div class="form-holder">
                        <span class="lnr lnr-user text-white"></span>
                        <input type="text" class="form-control-l" name="username" autocomplete="username" placeholder="Masukan Username" value="{{ old('username') }}" required>
                        <div class="invalid-feedback fw-bold mt-2 mb-3" style="position: absolute;">
                            Username wajib diisi.
                        </div>
                    </div>
                    <div class="form-holder mt-4">
                        <span class="lnr lnr-lock text-white"></span>
                        <input type="password" class="form-control-l" name="password" placeholder="Masukan Password" required>
                        <div class="invalid-feedback fw-bold mt-2" style="position: absolute;">
                            Password wajib diisi.
                        </div>
                    </div>
                    @if (session('error'))
                    <div class="alert alert-danger fs-14 text-center fw-bold" style="margin-bottom: -20px !important" role="alert">
                        Username / Password Salah.
                    </div>
                    @endif
                    <button type="submit" class="rounded">Masuk</button>
                </div>
            </form>
            <p class="text-center text-black-50 fw-bold fs-14 mt-2">PRESENSI © {{ date('Y') }}</p>
            <img src="{{ asset('assetLogin/images/image3.png') }}" alt="" class="image-2" style="margin-right: -100px !important; margin-bottom: -5px !important" width="300">
        </div>
    </div>

    <script src="{{ asset('assetLogin/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assetLogin/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
        })()
    </script>
</body>
</html>