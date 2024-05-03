<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverGeo extends Model
{

    protected $fillable = ['geo_id', 'driver_id', 'team_id'];

    public function geo(){
        return $this->belongsTo('App\Model\Geo' , 'geo_id', 'id')->select('id', 'name', 'description', 'zoom_level', 'geo_array'); 
    }

    public function agent(){
        return $this->belongsTo('App\Model\Agent', 'driver_id', 'id')->select('id','profile_picture','device_type','device_token','is_available');
        
    }
}
