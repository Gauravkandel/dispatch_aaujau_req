<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    //

    public function timezone_name($timezone_id){
        $timezonedetail = Timezone::where('id',$timezone_id)->orWhere('timezone',$timezone_id)->first('timezone');
        if($timezonedetail)
        {
            return $timezonedetail->timezone;
        }else{
            return "Asia/Kolkata";
        }
    }
}
