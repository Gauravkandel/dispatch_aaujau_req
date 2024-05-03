<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentPayout extends Model
{
    public function agent(){
	    return $this->hasOne('App\Model\Agent' , 'id', 'agent_id'); 
	}

	// public function user(){
	//     return $this->hasOne('App\Model\User' , 'id', 'requested_by'); 
	// }

    public function currencyDetails(){
        return $this->hasOne('App\Model\Currency','id','currency');
    }

    public function payoutOption(){
        return $this->hasOne('App\Model\PayoutOption' , 'id', 'payout_option_id');
    }

    public function payoutBankDetails(){
        return $this->hasMany('App\Model\AgentBankDetail' , 'id', 'agent_bank_detail_id');
    }

    public function getStatusAttribute($value){
        if($value == '1'){
            return 'Paid';
        }elseif($value == '2'){
            return 'Failed';
        }else{
            return 'Pending';
        }
    }
}
