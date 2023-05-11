<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Present extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tanggal', 'keterangan', 'jam_masuk', 'jam_keluar', 'foto_datang', 'foto_pulang', 'foto_permohonan', 'lokasi_datang', 'lokasi_pulang', 'status_permohonan', 'total_jam', 'denda', 'keterangan_atasan', 'updated_by'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    // public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fotoDatang()
    {
        if (Storage::disk('sftp')->exists($this->foto_datang)) {
            return config('app.ftp_src') . $this->foto_datang;
        } else {
            return config('app.ftp_src') . 'default.png';
        }
    }
    public function fotoPulang()
    {
        if (Storage::disk('sftp')->exists($this->foto_pulang)) {
            return config('app.ftp_src') . $this->foto_pulang;
        } else {
            return config('app.ftp_src') . 'default.png';
        }
    }
}
