<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{

	protected $guarded = [];

    public static function isHoliday(){
        $date = date('Y-m-d');
        $libur = HariLibur::wheretgl($date)->first();
        if($libur){
            return ["libur" => true,'dt' => $libur];
        }else{
            return ["libur" => false];
        }

    }
}
