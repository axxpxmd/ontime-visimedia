<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_lokasi', 'latitude', 'longitude','created_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($Location) {
            $Location->created_by = Auth::user()->id;
            // add other column as well
        });

        static::updating(function ($Location) {
            $Location->created_by = Auth::user()->id;
            // add other column as well
        });
    }
}
