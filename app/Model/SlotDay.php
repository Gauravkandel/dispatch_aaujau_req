<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SlotDay extends Model
{
    protected $fillable = ['slot_id','day'];

    public function agent_slot(){
	    return $this->belongsTo('App\Model\AgentSlot','slot_id','id'); 
	}
}
