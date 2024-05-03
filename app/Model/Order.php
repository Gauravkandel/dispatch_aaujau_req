<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Order extends Model
{
    protected $fillable = ['customer_id','scheduled_date_time','recipient_phone','Recipient_email','task_description','images_array','auto_alloction','driver_id','key_value_set','order_time','order_type','note','status'
    ,'cash_to_be_collected','base_price','base_duration','base_distance','base_waiting','duration_price','waiting_price','distance_fee','cancel_fee','agent_commission_percentage','agent_commission_fixed','freelancer_commission_percentage',
     'freelancer_commission_fixed','actual_time','actual_distance','order_cost','driver_cost','proof_image','proof_signature','unique_id','net_quantity','call_back_url', 'completion_otp','order_number','type','friend_name','friend_phone_number','request_type','is_restricted', 'vendor_id', 'order_vendor_id', 'sync_order_id','dbname','luxury_option_id'];

    public function customer(){
        return $this->hasOne('App\Model\Customer', 'id', 'customer_id');
        
    }
    public function location(){
        return $this->hasMany('App\Model\Location', 'customer_id', 'customer_id')
                    ->select('latitude', 'longitude', 'short_name', 'address', 'post_code', 'customer_id', 'flat_no');
        
    }

    public function task(){
        return $this->hasMany('App\Model\Task', 'order_id', 'id')->orderBy('task_order');
        
    }

    public function agent(){
        return $this->belongsTo('App\Model\Agent', 'driver_id', 'id')->select('id', 'team_id', 'name', 'type', 'phone_number','make_model', 'plate_number', 'profile_picture', 'vehicle_type_id','color');
        
    }

    public function teamtags(){
        return $this->belongsToMany('App\Model\TaskTeamTag', 'task_team_tags','task_id','tag_id');
    }
    public function drivertags(){
        return $this->belongsToMany('App\Model\TaskDriverTag', 'task_driver_tags','task_id','tag_id');
    }

    public function customerdata()
    {
      return $this->hasOne('App\Model\Customer');
    }

    public function taskFirst()
    {
      return $this->task()->where('pricing_rule_id', 1);
    }

    public function allteamtags(){
        return $this->hasMany('App\Model\TaskTeamTag','task_id','id');
    }

    public function task_rejects(){
        return $this->hasMany('App\Model\TaskReject','order_id','id');
    }

    public function first_task_order_by_date(){
        return $this->hasOne('');
    }

    public function getTaskImagesAttribute($value)
    {
      $array = array();
      $imgarray = array();
      
      if (isset($value) && !empty($value)) {
        $array = explode(",", $value);
        } else {
            $array = []; 
        }

        $can = Storage::disk('s3')->url('image.png');
        $lastbaseurl = str_replace('image.png', '', $can);

        if(count($array) > 0){
            foreach ($array as $item)  {
                $imgarray[] = $lastbaseurl.$item;
           }
        }
        
        return $imgarray;
    }
    
}
