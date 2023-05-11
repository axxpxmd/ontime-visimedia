<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tmsanksi extends Model
{
    protected $table = 'tmsanksi';
    protected $fillable = ['nama'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($Tmsanksi) {
            $Tmsanksi->created_by = Auth::user()->id;
        });

        static::updating(function ($Tmsanksi) {
            $Tmsanksi->created_by = Auth::user()->id;
            // add other column as well
        });
    }

    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'tmsanksi_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'tmsanksi_id');
    }
}
