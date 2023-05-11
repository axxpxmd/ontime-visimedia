<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sanksi extends Model
{
    protected $table = 'sanksi';
    protected $fillable = ['nama','percent','tmsanksi_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($Sanksi) {
            $Sanksi->created_by = Auth::user()->id;
        });

        static::updating(function ($Sanksi) {
            $Sanksi->created_by = Auth::user()->id;
            // add other column as well
        });
    }

    public function tmsanksi()
    {
        return $this->belongsTo(Tmsanksi::class, 'tmsanksi_id');
    }
}
