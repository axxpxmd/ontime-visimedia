<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    protected $table = "opd";
    protected $fillable = ['nama', 'keterangan', 'alamat', 'logo'];

    public function unit_kerjas()
    {
        return $this->hasMany(UnitKerjaa::class);
    }
}
