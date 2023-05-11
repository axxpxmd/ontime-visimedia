<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unitkerja extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_kerja', 'initial'
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
}
