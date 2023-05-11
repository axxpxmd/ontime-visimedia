<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UnitKerjaa extends Model
{
    protected $table="unit_kerja";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'opd_id','nama','created_by',
    ];
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

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }
    public function sub_unitkerjas()
    {
        return $this->hasMany(SubUnitKerja::class, 'unit_kerja_id');
    }
}
