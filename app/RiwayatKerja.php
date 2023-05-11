<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatKerja extends Model
{
    protected $table = "riwayat_kerja";
    protected $fillable = [
    'personal_information_id',
    'tahun',
    'nomer_sk',
    'tanggal_sk',
    'pejabat_sk',
    'tmt_jabatan',
    'jabatan_id',
    'status','dokumen',
    'keterangan'

];

    public function personal_information()
    {
        return $this->belongsTo(PersonalInformation::class, 'personal_information_id');
    }
    public function jabatan()
    {
        return $this->belongsTo(JenisJabatan::class, 'jabatan_id');
    }
}
