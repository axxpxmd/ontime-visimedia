<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SubUnitKerja extends Model
{
    protected $table="subunit_kerja";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

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
    protected $fillable = [
        'unit_kerja_id','nama','created_by',
    ];

    public function unitkerja()
    {
        return $this->belongsTo(UnitKerjaa::class, 'unit_kerja_id');
    }

    public function pi()
    {
        return $this->hasMany(PersonalInformation::class, 'subunit_kerja_id');
    }
}
