<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    protected $table = 'personal_information';
    protected $fillable = ['nama','atasan_id','opd_id','unit_kerja_id','subunit_kerja_id', 'nik', 'gelar_depan', 'gelar_belakang', 'tempat_lahir', 'tanggal_lahir', 'npwp', 'jenis_kelamin', 'agama', 'status_kawin', 'email', 'no_telp', 'golongan_darah', 'alamat_ktp', 'alamat_domisili', 'user_id','gaji'];

    public function UnitKerja()
    {
        return $this->belongsTo(UnitKerjaa::class, 'unit_kerja_id');
    }
    public function SubUnitKerja()
    {
        return $this->belongsTo(SubUnitKerja::class, 'subunit_kerja_id');
    }
    public function Opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }
    public function riwayat_kerja()
    {
        return $this->hasMany(RiwayatKerja::class, 'personal_information_id');
    }
    public function riwayat_pelatihan()
    {
        return $this->hasMany(RiwayatPelatihan::class, 'personal_information_id');
    }
    public function riwayat_pendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'personal_information_id');
    }
}
