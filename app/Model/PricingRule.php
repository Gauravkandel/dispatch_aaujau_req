<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    protected $table = 'price_rules';
    protected $fillable = ['name','start_date_time','end_date_time','is_default','geo_id','team_id','team_tag_id','driver_tag_id','base_price','base_duration','base_distance','base_waiting','duration_price','waiting_price','distance_fee','cancel_fee','agent_commission_percentage','agent_commission_fixed','freelancer_commission_percentage','agent_commission_fixed', 'apply_timetable'];


    /* public function team(){
        return $this->belongsTo('App\Model\Team');
    }

    public function tagsForAgent(){
        return $this->belongsTo('App\Model\TagsForAgent','driver_tag_id','id');
    } */

    public function priceRuleTimeframe(){
        return $this->hasMany('App\Model\priceRuleTimeframe', 'pricing_rule_id', 'id');
        
    }

    public function priceRuleTags(){
        return $this->hasMany('App\Model\priceRuleTag','pricing_rule_id','id');
    }

}
