<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class priceRuleTimeframe extends Model
{
    protected $fillable = ['pricing_rule_id', 'day_name', 'is_applicable', 'start_time', 'end_time'];
    
    public function pricerule(){
        return $this->belongsTo('App\Model\PricingRule','pricing_rule_id','id');
    }
}
