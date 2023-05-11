<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    protected $table = "riwayat_pendidikan";
    protected $fillable = [


        'personal_information_id',
        'tingkat',
        'jurusan',
        'lembaga',
        'nomor_ijazah',
        'tgl_ijazah',
        'tahun_lulus',
        'nilai',
        'dokumen',
        'keterangan',
        'status'



];

    public function personal_information()
    {
        return $this->belongsTo(PersonalInformation::class, 'personal_information_id');
    }
}
