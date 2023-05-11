<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Activitie extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tanggal', 'aktifitas', 'jam_mulai', 'jam_selesai', 'keterangan', 'c_verifikasi', 'd_verifikasi', 'foto'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getFotoUrlAttribute($value)
    {
        if (Storage::disk('sftp')->exists($value)) {
            return config('app.ftp_src') . $value;
        } else {
            return '';
        }
    }
}
