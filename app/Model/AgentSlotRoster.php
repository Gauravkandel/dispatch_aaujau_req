<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentSlotRoster extends Model
{
    //
    protected $fillable = ['agent_id','start_time','end_time','schedule_date','booking_type','memo','slot_id'];

    public function deleteVendorSlotDates($agent_id)
    {
    	return $this->where('agent_id',$agent_id)->delete();
    }
    public function agentSlot()
    {
        return $this->hasOne('App\Model\AgentSlot','id','slot_id'); 
    }
    public function days(){
        return $this->hasMany('App\Model\SlotDay', 'slot_id', 'slot_id'); 
    }

}
