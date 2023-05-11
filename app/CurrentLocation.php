<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentLocation extends Model
{
    protected $table = "current_location";
    protected $fillable = ["user_id","lat","long","device","date"];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
