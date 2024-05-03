<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    protected $fillable = ['name','description','zoom_level','geo_array','client_id','polygon'];

    public function agents(){
        return $this->belongsToMany('App\Model\Agent', 'driver_geos','geo_id','driver_id');
    }

    protected $appends = ['geo_coordinates'];

    public function getGeoCoordinatesAttribute(){
        $data = [];  
        $temp = $this->geo_array;
        $temp = str_replace('(','[',$temp);
        $temp = str_replace(')',']',$temp);
        $temp = '['.$temp.']';
        $temp_array =  json_decode($temp,true);

        foreach($temp_array as $k=>$v){
            $data[] = [
                'lat' => $v[0],
                'lng' => $v[1]
            ];
        }
        return $data;
    }
}
