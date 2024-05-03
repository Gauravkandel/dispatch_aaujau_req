<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlansDriver extends Model
{
    use SoftDeletes;
    protected $table = "subscription_plans_driver";

    // public function features(){
    //     return $this->hasMany('App\Models\SubscriptionPlanFeaturesUser', 'subscription_plan_id', 'id');
    // }

    // public function subFeatures(){
    //     return $this->belongsToMany('App\Models\SubscriptionFeaturesListUser', 'subscription_plan_features_user', 'subscription_plan_id', 'feature_id');
    // }

    public function getImageAttribute($value)
    {
        if(!empty($value)){
            return \Storage::disk('s3')->url($value);
        }
        return null;
    }
}
