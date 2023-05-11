<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'role_id','uker_id','atasan_id','nama', 'nrp', 'foto', 'password',
        'role_id', 'uker_id', 'atasan_id', 'lokasi_id', 'nama', 'username', 'foto', 'password','sk','sallary','shift_id','s_aktif','s_akun','tmsanksi_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'lokasi_id' => 'array',
    ];


    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    private function getUserRole()
    {
        return $this->role()->getResults();
    }

    private function checkRole($role)
    {
        return (strtolower($role) == strtolower($this->have_role->role)) ? true : false;
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();

        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->checkRole($need_role)) {
                    return true;
                }
            }
        } else {
            return $this->checkRole($roles);
        }
    }

    public function unitkerjas()
    {
        return $this->belongsTo(Unitkerja::class, 'uker_id');
    }

    public function unitkerja()
    {
        return $this->belongsTo(Unitkerja::class, 'uker_id');
    }

    public function presents()
    {
        return $this->hasMany('App\Present');
    }
    public function getFotoAttribute($value)
    {
        if (Storage::disk('sftp')->exists($value)) {
            return config('app.ftp_src') . $value;
        } else {
            return config('app.ftp_src') . 'default.png';
        }
    }
    public function getPhoto()
    {
        if (Storage::disk('sftp')->exists($this->foto)) {
            return config('app.ftp_src') . $this->foto;
        } else {
            return config('app.ftp_src') . 'default.png';
        }
    }
    public function getSk()
    {
        if (Storage::disk('sftp')->exists($this->sk)) {
            return config('app.ftp_src') . $this->sk;
        } else {
            return '';
        }
    }
    public static function getLokasi($id)
    {
        $lokasi_id = User::whereid($id)->first()->lokasi_id;
        $lokasi = [];
        if (is_array($lokasi_id)) {
            foreach ($lokasi_id as $v) {
                $lok = Location::whereid($v)->first();
                if ($lok) {
                    array_push($lokasi, ['latitude' => $lok->latitude, 'longitude' => $lok->longitude]);
                }
            }
        }
        return $lokasi;
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
    public function routeNotificationForFcmAll()
    {
        return $this->getDeviceTokens();
    }

    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class, 'user_id');
    }
}
