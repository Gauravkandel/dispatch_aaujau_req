<?php

namespace App\Model;

use Carbon\Carbon;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\WalletFloat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use Thumbor\Url;


class Agent extends Authenticatable implements  Wallet, WalletFloat
{
	use Notifiable;
    use HasWallet;
    use HasWalletFloat;
    use SoftDeletes;

    protected $fillable = [
        'team_id', 'name', 'profile_picture', 'type', 'vehicle_type_id', 'make_model', 'plate_number', 'phone_number', 'color', 'is_activated', 'is_available','cash_at_hand','uid', 'is_approved','customer_type_id'
    ];

    protected $appends = ['image_url', 'agent_cash_at_hand'];

    public function day(){
        $mytime = Carbon::now();
        return $this->hasMany('App\Models\SlotDay', 'slot_id', 'id')->where('day', $mytime->dayOfWeek+1); 
    }
    public function days(){
        return $this->hasOne('App\Models\SlotDay', 'slot_id', 'id'); 
    }
    public function getImageUrlAttribute()
    {
        $secret = '';
        $server = 'http://192.168.100.211:8888';
        //$new    = \Thumbor\Url\Builder::construct($server, $secret, 'http://images.example.com/llamas.jpg')->fitIn(90,50);
        return    \Storage::disk("s3")->url($this->profile_picture);
        ///return $new; 

    }
    public function slots(){
        return $this->hasMany('App\Model\AgentSlotRoster', 'agent_id', 'id');
      }

    // public function build()
    // {
    //     return new Url(
    //         $this->server,
    //         $this->secret,
    //         $this->original,
    //         $this->commands->toArray()
    //     );
    // }

    public function getAgentCashAtHandAttribute()
    {

    $credit = $this->agentPayment->sum('cr');
    $debit = $this->agentPayment->sum('dr');

    $wallet_balance = 0;
    if($this->wallet){
    $wallet_balance = $this->balanceFloat;
    }
    $cash = $this->completeOrder->sum('cash_to_be_collected');
    $driver_cost = $this->completeOrder->sum('driver_cost');

    $available_funds = ($credit + $cash) - ($wallet_balance + $debit + $driver_cost) ;

    return $available_funds;
    }

    public function completeOrder(){
    return $this->hasMany('App\Model\Order','driver_id', 'id')->where('status', 'completed');
    }
   

    public function team(){
       return $this->belongsTo('App\Model\Team')->select("id", "name", "location_accuracy", "location_frequency"); 
    }

    public function logs(){
        return $this->hasOne('App\Model\AgentLog' , 'agent_id','id')->select("id", "agent_id", "lat", "long"); 
    }

    public function vehicle_type(){
        return $this->belongsTo('App\Model\VehicleType'); 
    }

    public function geoFence(){
        return $this->hasMany('App\Model\DriverGeo' , 'driver_id', 'id')->select('driver_id', 'geo_id'); 
    }

    public function order(){
        return $this->hasMany('App\Model\Order','driver_id', 'id');
    }
    public function agentPayment(){
        return $this->hasMany('App\Model\AgentPayment','driver_id', 'id');
    }
    public function agentlog(){
        return $this->hasOne('App\Model\AgentLog','agent_id', 'id')->latest();
    }

    public function tags(){
        return $this->belongsToMany('App\Model\TagsForAgent', 'agents_tags','agent_id','tag_id');
    }

    public function agentfirstlog(){
        return $this->hasOne('App\Model\AgentLog','agent_id', 'id');
    }

    public function agentBankDetails(){
        return $this->hasMany('App\Model\AgentBankDetail' , 'id', 'agent_id');
    }

    public function subscriptionPlan(){
        return $this->hasOne('App\Model\SubscriptionInvoicesDriver' , 'driver_id', 'id')->orderBy('end_date', 'desc');
    }

}