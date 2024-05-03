<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class orderTemp extends Model
{
    protected $fillable =  ['order_id','order_order','geo_id','task_id','task_lat','task_long'];
}
