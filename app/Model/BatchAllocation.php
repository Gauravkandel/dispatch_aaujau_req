<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BatchAllocation extends Model
{
    
    protected $fillable = ['batch_no','geo_id','agent_id','batch_time', 'batch_type'];


    public function task(){
        return $this->hasMany('App\Model\BatchAllocationDetail', 'order_id', 'id')->orderBy('task_order');
    }

    public function batchDetails(){
        return $this->hasMany('App\Model\BatchAllocationDetail','batch_no', 'batch_no');
    }

    public function agent(){
        return $this->belongsTo('App\Model\Agent', 'agent_id', 'id')->select('id', 'team_id', 'name', 'type', 'phone_number','make_model', 'plate_number', 'profile_picture', 'vehicle_type_id','color');
        
    }

}
