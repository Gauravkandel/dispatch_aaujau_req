<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;
    protected $guard = 'client';
    protected $fillable = [
        'name', 'email', 'password', 'phone_number','dial_code', 'password', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain','sub_domain','status','code','confirm_password','is_superadmin','all_team_access','country_id','timezone','public_login_session', 'socket_url', 'dark_logo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get Clientpreference
    */
    public function getPreference()
    {
      return $this->hasOne('App\Model\ClientPreference','client_id','code');
    }

    /**
     * Get Allocation Rules
    */
    public function getAllocation()
    {
      return $this->hasOne('App\Model\AllocationRule','client_id','code');
    }


    public function getCodeAttribute($value)
    { 
      if(!empty($this->attributes['id'])){
        $value = str_replace($this->attributes['id']."_",'',$value);
      }
      return $value;
    }


     /**
     * Get All permisions
    */
    public function getAllPermissions()
    {
      return $this->hasMany('App\Model\SubAdminPermissions','sub_admin_id','id');
    }

    /**
     * Get All teams
    */
    public function getAllTeams()
    {
      return $this->hasMany('App\Model\SubAdminTeamPermissions','sub_admin_id','id');
    }


    /**
     * Get Clientpreference
    */
    public function getCountrySet()
    {
      return $this->belongsTo('App\Model\Countries','country_id','id');
    }

    /**
     * Get timezone
    */
    public function getTimezone()
    {
      return $this->belongsTo('App\Model\Timezone','timezone','id');
    }
}