<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogDenda extends Model
{
    protected $table = 'log_denda';
    protected $fillable = ['present_id','kategori','denda'];
}
