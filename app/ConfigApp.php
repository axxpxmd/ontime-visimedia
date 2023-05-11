<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigApp extends Model
{
    protected $table = "config_apps";
    protected $fillable = ["app_id","nama","pemilik","icon"];
}
