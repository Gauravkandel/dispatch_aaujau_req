<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoicesDriver extends Model
{
    protected $table = "subscription_invoices_driver";

    public function plan(){
        return $this->belongsTo('App\Model\SubscriptionPlansDriver', 'subscription_id', 'id')->withTrashed(); 
    }

    public function user(){
        return $this->belongsTo('App\Model\Agent', 'user_id', 'id'); 
    }

    // public function features(){
    //     return $this->hasMany('App\Model\SubscriptionInvoiceFeaturesUser', 'subscription_invoice_id', 'id'); 
    // }

    public function payment(){
        return $this->hasOne('App\Model\Payment', 'user_subscription_invoice_id', 'id'); 
    }
}
