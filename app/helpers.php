<?php

use App\Opd;
use App\Role;
use App\User;
use App\Sanksi;
use App\JamKerja;
use App\LogDenda;
use Carbon\Carbon;
use App\PersonalInformation;

if (!function_exists('jam_total')) {
    function jam_total($masuk, $keluar)
    {
        $jamMasuk  = Carbon::parse($masuk);
        $jamKeluar = Carbon::parse($keluar);

        //* get total
        $diff = $jamMasuk->diff($jamKeluar);
        $diffHours   = sprintf("%02d", $diff->h);
        $diffMinutes = sprintf("%02d", $diff->i);
        $diffSecound = sprintf("%02d", $diff->s);

        return $diffHours . ':' . $diffMinutes . ':' . $diffSecound;
    }
}
if (!function_exists('total_sanksi')) {
    function total_sanksi($user_id, $jam_masuk, $tanggal, $jam_keluar="")
    {
        $user = User::whereid($user_id)->first();


        if ($user->sallary > 0) {
            $jamkerja = JamKerja::whereN(date('N', strtotime($tanggal)))->where('shift_id', $user->shift_id)->first();
            $denda = 0;
            if ($jamkerja) {
                if ($jam_masuk) {
                    if (strtotime($jam_masuk) > strtotime($jamkerja->mulai_sanksi) && strtotime($jam_masuk) < strtotime($jamkerja->mulai_sanksi2)) {
                        $keterangan = 'Sanksi 1';
                    }

                    if (strtotime($jam_masuk) > strtotime($jamkerja->mulai_sanksi2)) {
                        $keterangan = 'Sanksi 2';
                    }
                    if (isset($keterangan)) {
                        $sanksi = Sanksi::where('tmsanksi_id', $user->tmsanksi_id)->wherenama($keterangan)->first();
                        if ($sanksi) {
                            $jml_denda = ($user->sallary * 50/100) * $sanksi->percent / 100;
                            $denda +=  $jml_denda;
                        }
                    }
                }

                if ($jam_keluar) {
                    if (strtotime($jam_keluar) < strtotime($jamkerja->selesai_kerja)) {
                        $keterangan = 'Pulang Cepat';
                        $sanksi = Sanksi::where('tmsanksi_id', $user->tmsanksi_id)->wherenama($keterangan)->first();
                        if ($sanksi) {
                            $jml_denda = ($user->sallary * 50/100) * $sanksi->percent / 100;
                            $denda +=  $jml_denda;
                        }
                    }
                }

                if (!$jam_masuk &&!$jam_keluar) {
                    $keterangan = 'Alpha';
                    $sanksi = Sanksi::where('tmsanksi_id', $user->tmsanksi_id)->wherenama($keterangan)->first();
                    if ($sanksi) {
                        $jml_denda = ($user->sallary * 50/100) * $sanksi->percent / 100;
                        $denda +=  $jml_denda;
                    }
                }

                return $denda;
            }


            return 0;
        }

        return 0;
    }
}
if (!function_exists('total_sanksi2')) {
    function total_sanksi2($present)
    {
        $user_id =$present->user_id;
        $jam_masuk = $present->jam_masuk;
        $tanggal = $present->tanggal;
        $jam_keluar=$present->jam_keluar;
        $user = User::whereid($user_id)->first();
        if (!isset($user->personalInformation)) {
            return 0;
        }
        if ($user->personalInformation->subunit_kerja_id == 600) {
            return 0;
        }
        if ($user->sallary > 0) {
            $jamkerja = JamKerja::whereN(date('N', strtotime($tanggal)))->where('shift_id', $user->shift_id)->first();
            $denda = 0;
            if ($jamkerja) {
                if ($jam_masuk) {
                    if (strtotime($jam_masuk) > strtotime($jamkerja->mulai_sanksi) && strtotime($jam_masuk) < strtotime($jamkerja->mulai_sanksi2)) {
                        $keterangan = 'Sanksi 1';
                    }

                    if (strtotime($jam_masuk) > strtotime($jamkerja->mulai_sanksi2)) {
                        $keterangan = 'Sanksi 2';
                    }

                    if (isset($keterangan)) {
                        $sanksi = Sanksi::where('tmsanksi_id', $user->tmsanksi_id)->wherenama($keterangan)->first();
                        if ($sanksi) {
                            $jml_denda = ($user->sallary * 50/100) * $sanksi->percent / 100;
                            $denda +=  $jml_denda;

                            LogDenda::updateOrCreate(['present_id'=>$present->id,
                        'kategori'=>$keterangan], [
                            'denda'=>$jml_denda,
                        ]);
                        }
                    } else {
                        $logDenda = LogDenda::where('present_id', $present->id)->whereIn('kategori', ['Sanksi 1','Sanksi 2']);
                        if ($logDenda->count() > 0) {
                            $logDenda->delete();
                        }
                    }
                }

                if ($jam_keluar) {
                    if (strtotime($jam_keluar) < strtotime($jamkerja->selesai_kerja)) {
                        $keterangan = 'Pulang Cepat';
                        $sanksi = Sanksi::where('tmsanksi_id', $user->tmsanksi_id)->wherenama($keterangan)->first();
                        if ($sanksi) {
                            $jml_denda = ($user->sallary * 50/100) * $sanksi->percent / 100;
                            $denda +=  $jml_denda;

                            LogDenda::updateOrCreate(['present_id'=>$present->id,
                        'kategori'=>$keterangan], [
                            'denda'=>$jml_denda,
                        ]);
                        }
                    } else {
                        $logDenda = LogDenda::where('present_id', $present->id)->whereIn('kategori', ['Pulang Cepat']);
                        if ($logDenda->count() > 0) {
                            $logDenda->delete();
                        }
                    }
                }

                if (!$jam_masuk &&!$jam_keluar) {
                    $keterangan = 'Alpha';
                    $sanksi = Sanksi::where('tmsanksi_id', $user->tmsanksi_id)->wherenama($keterangan)->first();
                    if ($sanksi) {
                        $jml_denda = ($user->sallary * 50/100) * $sanksi->percent / 100;
                        $denda +=  $jml_denda;
                        LogDenda::updateOrCreate(['present_id'=>$present->id,
                        'kategori'=>$keterangan], [
                            'denda'=>$jml_denda,
                        ]);
                    }
                } else {
                    $logDenda = LogDenda::where('present_id', $present->id)->whereIn('kategori', ['Alpha']);
                    if ($logDenda->count() > 0) {
                        $logDenda->delete();
                    }
                }

                return $denda;
            }


            return 0;
        }

        return 0;
    }
}
if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
}
if (!function_exists('getUnitKerja')) {
    function getUnitKerja($id)
    {
        $personal = PersonalInformation::whereuser_id($id)->first();
        if ($personal) {
            if (isset($personal->SubUnitKerja)) {
                return $personal->SubUnitKerja->nama;
            }
            if (isset($personal->UnitKerja)) {
                return $personal->UnitKerja->nama;
            }
            if (isset($personal->Opd)) {
                return $personal->Opd->nama;
            }

            return '-';
        }
    }
}
if (!function_exists('getWho')) {
    function getWho()
    {
        $auth = auth()->user()->role_id;
        $pi = auth()->user()->personalInformation;
        if ($auth == 1) {
            $data['user'] = new User();
            $data['opds'] = Opd::all();
            $data['roles'] = Role::all();
        } else {
            $data['user'] = User::select('users.*')
                        ->join('personal_information', 'users.id', 'personal_information.user_id')
                        ->where('personal_information.opd_id', $pi->opd_id);

            $data['opds'] = Opd::whereid($pi->opd_id)->get();
            $data['roles'] = Role::whereNotIn('id', [1,7,6])->get();
        }



        $data['role_id'] = auth()->user()->role_id;

        return $data;
    }
}
if (!function_exists('checkCreatedBy')) {
    function checkCreatedBy($data)
    {
        $auth = auth()->user()->role_id;
        if ($auth == 1) {
            return true;
        }

        $auth = auth()->user();
        if ($auth->id !=  $data->created_by) {
            return false;
        }
        return true;
    }
}
