<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPelatihan extends Model
{
    protected $table = "riwayat_pelatihan";
    protected $fillable = [

        'personal_information_id',
        'nama_kegiatan',
        'penyelenggara',
        'nomor_sertifikat',
        'tanggal_sertifikat',
        'status',
        'dokumen',
        'keterangan',


];

    public function personal_information()
    {
        return $this->belongsTo(PersonalInformation::class, 'personal_information_id');
    }
}
