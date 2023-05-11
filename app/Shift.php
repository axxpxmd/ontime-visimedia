<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = "shifts";
    protected $fillable = ['name','created_by'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($UnitKerjaa) {
            $UnitKerjaa->created_by = Auth::user()->id;
            // add other column as well
        });

        static::updating(function ($UnitKerjaa) {
            $UnitKerjaa->created_by = Auth::user()->id;
            // add other column as well
        });
    }

    public function jamKerja()
    {
        return $this->hasMany(JamKerja::class, 'shift_id');
    }
}
