<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskReject extends Model
{
    protected $fillable = ['order_id','driver_id','status'];

    public function agent(){
        return $this->belongsTo('App\Model\Agent', 'driver_id', 'id')->select('id', 'team_id', 'name', 'profile_picture');
    }
}
