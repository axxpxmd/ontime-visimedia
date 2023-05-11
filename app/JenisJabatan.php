<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisJabatan extends Model
{
    protected $table= 'jenis_jabatan';
    protected $fillable = ['nama','teknis'];
}
