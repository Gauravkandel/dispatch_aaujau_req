<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentBankDetail extends Model
{
    public function agent(){
	    return $this->hasOne('App\Model\Agent' , 'id', 'agent_id'); 
	}

    public function payoutOption(){
        return $this->hasOne('App\Model\PayoutOption' , 'id', 'payout_option_id');
    }

}
