<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    protected $table = "jam_kerja";
    protected $fillable = ['shift_id','N','hari','mulai_absen','mulai_kerja','selesai_kerja','mulai_checkout','mulai_sanksi','mulai_sanksi2','maks_absen','created_at'];

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($UnitKerjaa) {
            $UnitKerjaa->created_by = Auth::user()->id;
        });

        static::updating(function ($UnitKerjaa) {
            $UnitKerjaa->created_by = Auth::user()->id;
            // add other column as well
        });
    }
}
