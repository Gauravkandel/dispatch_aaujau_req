<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Events\PushNotification;

class Roster extends Model
{
    protected $fillable = ['order_id','driver_id','notification_time','type','client_code','detail_id','request_type','status'];

    public function agent(){
        return $this->hasOne('App\Model\Agent', 'id', 'driver_id')->select('id', 'team_id', 'name', 'type', 'phone_number','device_type','device_token');
        
    }

    protected $dispatchesEvents = [
        'saved' => PushNotification::class,
    ];
}
