<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CustomerVerificationResource extends Model
{
    protected $fillable = [
        'customer_id', 'verification_type', 'datapoints'
    ];
    protected $appends = ['resources'];
    public function getResourcesAttribute($value){
        return json_decode($this->datapoints);
    }
}
