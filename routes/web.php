<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Opd;
use App\User;
use App\Present;
use App\JamKerja;
use App\HariLibur;
use App\SubUnitKerja;
use GuzzleHttp\Client;
use App\Imports\UkerImport;
use App\PersonalInformation;

use App\Helpers\AccountActivated;
use App\Notifications\TestingNotif;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/zzz', function () {
    $p = PersonalInformation::all();
    foreach ($p as $i) {
        if ($i->gaji > 0) {
            User::whereid($i->user_id)->update([
                'sallary' => $i->gaji
            ]);
        }
    }
});
Route::get('/testImport', function () {
    $message = 'Mohon lengkapi profile anda (Biodata, Riwayat Kerja, Pendidikan, Pelatihan) di aplikasi Presensi web based " https://bit.ly/3v3cFaP "';
    $token = User::whereNotNull('token_firebase')->pluck('token_firebase')->toArray();

    $client = new Client([
                'headers' => [ 'Content-Type' => 'application/json' ,'Authorization' => 'key=AAAAvSZT2Gc:APA91bH-T0h9oJvaRkZln_ABtHrWq-mIDf7URhlmCj-vs3TPVaDaedN7IAPsQLbAsqCKld4aIGf09HLXWsMnEDEZMXiVSAidhWRLQFeyz-gqHzbuBeRi9E8YfvgYu_tY9nqKMp9AQdo2'],
            ]);
    $response = $client->post(
        'https://fcm.googleapis.com/fcm/send',
        ['body' => json_encode(
            [
                'registration_ids' =>$token,
                "priority"=> "high",
                'notification' => [
                    'title' => 'Presensi',
                    'body' => $message,
                ],

            ]
        )]
    );

    dd($response);
    $rows = Excel::import(new UkerImport(), public_path('uker.xls'));

    return response()->json(["rows"=>$rows]);
    return;

    $user = User::all();
    foreach ($user as $us) {
        if ($us->uker_id) {
            $subunit = SubUnitKerja::whereRaw('lower(nama) = ? ', [strtolower($us->unitkerja->unit_kerja)])->first();
            if ($subunit) {
                $units = $subunit->unitkerja;
                $unit = $subunit->unitkerja->id;
                if ($units) {
                    $opd = $units->opd->id;
                }
            }
        } else {
            $subunit = null;
        }
        PersonalInformation::updateOrCreate(["user_id" => $us->id], [
            "nama" => $us->nama,
            "gaji" => $us->sallary,
            "subunit_kerja_id" => isset($subunit->id) ? $subunit->id : $subunit,
            "unit_kerja_id" => isset($unit) ? $unit : null,
            "opd_id" => isset($opd) ? $opd : null,
            "atasan_id" => $us->atasan_id,
        ]);
    }
});

Route::get('/createAdminOpd', function () {
    $opd = Opd::all();
    $data = [];
    foreach ($opd as $i) {
        $user = [
            'nama' => $i->nama,
            'username' => 'adm.'.str_replace(',', '', str_replace(' ', '.', strtolower($i->nama))),
            'password' => Hash::make(123456789),
            'role_id' => 7
        ];

        $user = User::updateOrCreate($user);
        PersonalInformation::updateOrCreate([
            'nama' => $i->nama,
            'user_id' => $user->id,

            'opd_id' => $i->id,


        ]);
    }

    dd($data);
});
Route::get('/support', 'SupportController@support');
Route::get('/apps', 'SupportController@apps');
Route::get('/apps/OnTime/manifest.plist', 'SupportController@ontime');


// Route::get('/hitung-denda', function () {
//     // $p = Present::whereRaw('WEEKDAY(presents.tanggal) = 6')->get();
//     // // dd($p);
//     // foreach ($p as $q) {
//     //     Present::whereid($q->id)->update(['keterangan' => "Libur"]);
//     // }
//     // $libur = HariLibur::pluck('tgl')->toArray();
//     // $p = Present::whereIn('tanggal', $libur)->get();
//     // foreach ($p as $q) {
//     //     Present::whereid($q->id)->update(['keterangan' => "Libur"]);
//     // }
//     // Present::

//     $present = Present::whereIn('keterangan', ['Telat','Alpha'])->whereMonth('tanggal', date('m'))->get();

//     foreach ($present as $p) {
//         $denda = total_sanksi($p->user_id, $p->jam_masuk, $p->tanggal);
//         if ($denda) {
//             Present::whereid($p->id)->update(['denda' => $denda]);
//         }
//     }

//     echo 'Hitung Denda Selesai';

//     // $present = Present::whereid(538)->first();
//     // $denda = total_sanksi($present->user_id, $present->jam_masuk, $present->tanggal);
//     // dd($denda);
// });

// Route::get('/hitung-jamkerja', function () {
//     $present = Present::whereIn('keterangan', ['Telat','Masuk'])->get();
//     foreach ($present as $p) {
//         $jamkerja = JamKerja::whereN(date('N', strtotime($p->tanggal)))->first();
//         if ($p->jam_keluar != '') {
//             if (strtotime($p->jam_keluar) > strtotime($jamkerja->selesai_kerja)) {
//                 $dt['total_jam'] = jam_total($p->jam_masuk, $jamkerja->selesai_kerja);
//                 $dt['total_lembur'] = jam_total($jamkerja->selesai_kerja, $p->jam_keluar);
//             } else {
//                 $dt['total_jam'] = jam_total($p->jam_masuk, $p->jam_keluar);
//                 $dt['total_lembur'] = null;
//             }
//         } else {
//             $dt['total_jam'] = null;
//             $dt['total_lembur'] = null;
//         }



//         Present::whereid($p->id)->update($dt);
//     }

//     echo 'Hitung Jam Kerja Selesai';

//     // $present = Present::whereid(538)->first();
//     // $denda = total_sanksi($present->user_id, $present->jam_masuk, $present->tanggal);
//     // dd($denda);
// });

Route::get('/login', 'AuthController@index')->name('auth.index')->middleware('guest');
Route::post('/login', 'AuthController@login')->name('login')->middleware('guest');

Route::get('/home', 'HomeController@index2')->name('home');
Route::get('/ehhh', 'HomeController@index');

Route::group(['middleware' => ['web', 'auth', 'roles']], function () {
    Route::post('/logout', 'AuthController@logout')->name('logout');
    Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/api-dashboard', 'HomeController@api_dashboard')->name('dashboard.api');
    Route::get('/ganti-password', 'UsersController@gantiPassword')->name('ganti-password');
    Route::patch('/update-password/{user}', 'UsersController@updatePassword')->name('update-password');
    Route::patch('/update-akun', 'UsersController@updateAkun')->name('update-akun');
    Route::patch('/update-personal', 'UsersController@updatePersonal')->name('update-personal');
    Route::get('/profil', 'UsersController@profil')->name('profil');

    Route::get('/riwayat-kerja', 'UsersController@riwayat_kerja')->name('profil.riwayat_kerja');
    Route::get('/riwayat-kerja/{id}', 'UsersController@riwayat_kerja_edit')->name('riwayat_kerja.edit');
    Route::post('/riwayat-kerja', 'UsersController@riwayat_kerja_store')->name('riwayat_kerja.store');
    Route::patch('/riwayat-kerja/{id}', 'UsersController@riwayat_kerja_update')->name('riwayat_kerja.update');
    Route::delete('/riwayat/{id}', 'UsersController@remove_riwayat')->name('profil.remove_riwayat');

    Route::get('/riwayat-pelatihan', 'UsersController@riwayat_pelatihan')->name('profil.riwayat_pelatihan');
    Route::get('/riwayat-pelatihan/{id}', 'UsersController@riwayat_pelatihan_edit')->name('riwayat_pelatihan.edit');
    Route::post('/riwayat-pelatihan', 'UsersController@riwayat_pelatihan_store')->name('riwayat_pelatihan.store');
    Route::patch('/riwayat-pelatihan/{id}', 'UsersController@riwayat_pelatihan_update')->name('riwayat_pelatihan.update');

    Route::get('/riwayat-pendidikan', 'UsersController@riwayat_pendidikan')->name('profil.riwayat_pendidikan');
    Route::get('/riwayat-pendidikan/{id}', 'UsersController@riwayat_pendidikan_edit')->name('riwayat_pendidikan.edit');
    Route::post('/riwayat-pendidikan', 'UsersController@riwayat_pendidikan_store')->name('riwayat_pendidikan.store');
    Route::patch('/riwayat-pendidikan/{id}', 'UsersController@riwayat_pendidikan_update')->name('riwayat_pendidikan.update');




    Route::patch('/update-profil/{user}', 'ProfileController@updateProfil')->name('update-profil');
    Route::get('/users/me', 'UsersController@me')->name('users.me');
    Route::get('/users/{id}/import-absen', 'UsersController@importAbsen')->name('users.importAbsen');
    Route::post('/users/import-absen-1', 'UsersController@importAbsenStoreStep1')->name('users.importAbsenStoreStep1');
    Route::post('/users/import-absen-2', 'UsersController@importAbsenStoreStep2')->name('users.importAbsenStoreStep2');
    Route::get('/users/getAtasan', 'UsersController@getAtasan')->name('user.getAtasan');

    Route::post('/config/getUnit', 'ConfigAppController@getUnit')->name('config.getUnit');
    Route::post('/config/getSubUnit', 'ConfigAppController@getSubUnit')->name('config.getSubUnit');
    Route::group(['roles' => ['Admin','Eselon 4','Eselon 3','Eselon 2','Eselon 1','Admin OPD']], function () {
        Route::get('/users/cari', 'UsersController@search')->name('users.search');
        Route::get('/users/getUser', 'UsersController@getUser')->name('users.getUser');

        Route::get('/users/maps/get-loct', 'UsersController@get_location')->name('users.get_location');
        Route::get('/config/app', 'ConfigAppController@index')->name('config.index');
        Route::post('/config/app', 'ConfigAppController@store')->name('config.store');

        Route::patch('/users/password/{user}', 'UsersController@password')->name('users.password');
        Route::resource('/users', 'UsersController');

        Route::get('/unitkerjas/cari', 'UnitkerjasController@search')->name('unitkerjas.search');
        Route::resource('/unitkerjas', 'UnitkerjasController');

        Route::get('/locations/cari', 'LocationsController@search')->name('locations.search');
        Route::resource('/locations', 'LocationsController');

        Route::get('/kehadiran/cetak_pdf', 'PresentsController@cetak_pdf')->name('kehadiran.pdf');
        Route::get('/kehadiran', 'PresentsController@index2')->name('kehadiran.index');
        Route::get('/kehadiran2', 'PresentsController@index2')->name('kehadiran.index2');
        Route::get('/kehadiran/cari', 'PresentsController@search')->name('kehadiran.search');
        Route::get('/kehadiran/{user}/cari', 'PresentsController@cari')->name('kehadiran.cari');
        Route::get('/kehadiran/excel-users', 'PresentsController@excelUsers')->name('kehadiran.excel-users');
        Route::get('/kehadiran/{user}/excel-user', 'PresentsController@excelUser')->name('kehadiran.excel-user');
        Route::get('/kehadiran/reportDetail', 'PresentsController@reportDetail')->name('kehadiran.reportDetail');
        Route::post('/kehadiran/ubah', 'PresentsController@ubah')->name('ajax.get.kehadiran');
        Route::patch('/kehadiran/{kehadiran}', 'PresentsController@update')->name('kehadiran.update');
        Route::post('/kehadiran', 'PresentsController@store')->name('kehadiran.store');
        Route::delete('/kehadiran/{id}', 'PresentsController@destroy')->name('kehadiran.destroy');

        Route::get('/shift', 'JamKerjaController@index')->name('jamkerja.index');
        Route::get('/shift/create', 'JamKerjaController@create')->name('shift.create');
        Route::post('/shift', 'JamKerjaController@store_shift')->name('shift.store');
        Route::delete('/shift/{id}', 'JamKerjaController@destroy_shift')->name('shift.destroy');
        Route::get('/shift/{id}/edit', 'JamKerjaController@edit_shift')->name('shift.edit');
        Route::patch('/shift/{id}/update', 'JamKerjaController@update_shift')->name('shift.update');
        Route::get('/jam-kerja/{id}', 'JamKerjaController@jam_kerja')->name('jamkerja.jam_kerja');
        Route::get('/jam-kerja/{id}/edit', 'JamKerjaController@edit')->name('jamkerja.edit');
        Route::patch('/jam-kerja/{id}/update', 'JamKerjaController@update')->name('jamkerja.update');

        Route::get('/tmsanksi', 'SanksiController@list')->name('tmsanksi.index');
        Route::get('/tmsanksi/create', 'SanksiController@create_tmsanksi')->name('tmsanksi.create');
        Route::get('/tmsanksi/{id}/edit', 'SanksiController@edit_tmsanksi')->name('tmsanksi.edit');
        Route::delete('/tmsanksi/{id}', 'SanksiController@destroy_tmsanksi')->name('tmsanksi.destroy');
        Route::patch('/tmsanksi/{id}/update', 'SanksiController@update_tmsanksi')->name('tmsanksi.update');
        Route::post('/tmsanksi', 'SanksiController@store_tmsanksi')->name('tmsanksi.store');

        Route::get('/sanksi', 'SanksiController@index')->name('sanksi.index');
        Route::get('/sanksi/{id}/edit', 'SanksiController@edit')->name('sanksi.edit');
        Route::patch('/sanksi/{id}/update', 'SanksiController@update')->name('sanksi.update');

        Route::get('hari-libur/api', 'HariLiburController@api')->name('hari-libur.api');
        Route::resource('hari-libur', HariLiburController::class);


        Route::get('satker/api', 'SatuanKerjaController@api')->name('satker.api');
        Route::resource('satker', SatuanKerjaController::class);

        Route::get('satker/unitkerja/{id}/api', 'SatuanKerjaController@unitkerja_api')->name('unit_kerja.api');
        Route::get('satker/unitkerja/{id}', 'SatuanKerjaController@unitkerja_index')->name('unit_kerja.index');
        Route::get('satker/unitkerja/{id}/edit', 'SatuanKerjaController@unitkerja_edit')->name('unit_kerja.edit');
        Route::post('satker/unitkerja/', 'SatuanKerjaController@unitkerja_store')->name('unit_kerja.store');
        Route::patch('satker/unitkerja/{id}', 'SatuanKerjaController@unitkerja_update')->name('unit_kerja.update');
        Route::delete('satker/unitkerja/{id}', 'SatuanKerjaController@unitkerja_destroy')->name('unit_kerja.destroy');

        Route::get('satker/subunitkerja/{id}/api', 'SatuanKerjaController@subunitkerja_api')->name('subunit_kerja.api');
        Route::get('satker/subunitkerja/{id}', 'SatuanKerjaController@subunitkerja_index')->name('subunit_kerja.index');
        Route::get('satker/subunitkerja/{id}/edit', 'SatuanKerjaController@subunitkerja_edit')->name('subunit_kerja.edit');
        Route::post('satker/subunitkerja/', 'SatuanKerjaController@subunitkerja_store')->name('subunit_kerja.store');
        Route::patch('satker/subunitkerja/{id}', 'SatuanKerjaController@subunitkerja_update')->name('subunit_kerja.update');
        Route::delete('satker/subunitkerja/{id}', 'SatuanKerjaController@subunitkerja_destroy')->name('subunit_kerja.destroy');
    });

    Route::group(['roles' => ['Admin','Eselon 4','Eselon 3','Eselon 2','Eselon 1','Admin OPD']], function () {
        Route::get('/atasan/cari', 'UserAtasansController@search')->name('atasan.search');
        Route::patch('/atasan/password/{user}', 'UserAtasansController@password')->name('atasan.password');
        Route::resource('/atasan', 'UserAtasansController');

        Route::get('/atasanPresents', 'PresentsAtasanController@index')->name('atasanPresents.index');
        Route::get('/atasanPresents/cari', 'PresentsAtasanController@search')->name('atasanPresents.search');
        Route::get('/atasanPresents/{user}/cari', 'PresentsAtasanController@cari')->name('atasanPresents.cari');
        Route::resource('/atasanPresents', 'PresentsAtasanController');

        Route::get('/atasanActivities', 'ActivitiesAtasanController@index')->name('atasanActivities.index');
        Route::get('/atasanActivities/cari', 'ActivitiesAtasanController@search')->name('atasanActivities.search');
        Route::patch('/atasanActivities/{activities}', 'ActivitiesAtasanController@update')->name('atasanActivities.update');
        Route::post('/atasanActivities/{activities}/tolak', 'ActivitiesAtasanController@tolak')->name('atasanActivities.tolak');
        Route::resource('/atasanActivities', 'ActivitiesAtasanController');

        Route::get('permohonan/api-list', 'PermohonanController@api_list')->name('permohonan.api-list');
        Route::get('permohonan/list-show/{id}', 'PermohonanController@list_show')->name('permohonan.list-show');
        Route::patch('permohonan/list-show/{id}/update', 'PermohonanController@list_update')->name('permohonan.list-update');
        Route::get('permohonan/list', 'PermohonanController@list')->name('permohonan.list');
    });

    Route::group(['roles' => ['Pegawai']], function () {
        Route::get('/daftar-hadir/cetak_pdf', 'PresentsController@kehadiran_pdf')->name('daftar-hadir.pdf');
        Route::get('/daftar-hadir', 'PresentsController@show')->name('daftar-hadir');
        // Route::get('/permohonan/{present}', 'PresentsController@permohonan')->name('permohonan');
        // Route::post('/ajukan-permohonan', 'PresentsController@simpanPermohonan')->name('ajukan-permohonan');
        Route::get('/daftar-hadir/cari', 'PresentsController@cariDaftarHadir')->name('daftar-hadir.cari');

        Route::get('permohonan/api', 'PermohonanController@api')->name('permohonan.api');
        Route::resource('/permohonan', 'PermohonanController');
    });

    Route::group(['roles' => ['Admin','Admin OPD']], function () {
        Route::get('report', 'ReportController@index')->name('report.index');
        Route::get('report/api', 'ReportController@api')->name('report.api');
        Route::get('report/excel', 'ReportController@excel')->name('report.excel');
        Route::get('/config/pengumuman', 'ConfigAppController@pengumuman')->name('config.pengumuman');
        Route::post('/config/pengumuman', 'ConfigAppController@pengumuman_store')->name('config.pengumuman_store');
    });

    Route::get('/activities/cetak_pdf', 'ActivitiesPegawaiController@cetak_pdf')->name('activities.pdf');
    Route::get('/activities/cetak_pdf2', 'ActivitiesPegawaiController@cetak_pdf2')->name('activities.pdf2');
    Route::get('/activities/cari', 'ActivitiesPegawaiController@search')->name('activities.search');
    Route::patch('/activities/{activities}', 'ActivitiesPegawaiController@update')->name('activities.update');
    Route::get('/activities/{activities}/acc', 'ActivitiesPegawaiController@acc')->name('activities.acc');
    Route::patch('/activities/{activities}/acc', 'ActivitiesPegawaiController@update2')->name('activities.update2');
    Route::resource('/activities', 'ActivitiesPegawaiController');

    // ATUR IP ADDRESS DISINI
    // Route::group(['middleware' => [config('ipaddress.ip_address')]], function() {
    Route::patch('/absen/{kehadiran}', 'PresentsController@checkOut')->name('kehadiran.check-out');
    Route::post('/absen', 'PresentsController@checkIn')->name('kehadiran.check-in');
    // });

    Route::get('s/{vue_capture?}', function () {
        return view('layouts.spa');
    })->where('vue_capture', '[\/\w\.-]*');
});
