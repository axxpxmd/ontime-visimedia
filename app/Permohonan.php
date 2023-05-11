<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Permohonan extends Model
{
    protected $fillable =['user_id','jenis','keterangan_pemohon','keterangan_atasan','tanggal_dari','tanggal_sampai','status','file'];

    public function getFile(){
        if(Storage::disk('sftp')->exists($this->file)){
            return config('app.ftp_src').$this->file;
        }else{
            return config('app.ftp_src').'default.png';
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
