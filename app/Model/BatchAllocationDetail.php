<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BatchAllocationDetail extends Model
{
    protected $fillable = ['batch_no','order_id','geo_id','agent_id','order_time','order_type'];


    public function order()
    {
        return $this->belongsTo('App\Model\Order', 'order_id', 'id')->select('id', 'customer_id', 'driver_id', 'recipient_phone', 'Recipient_email', 'task_description', 'auto_alloction', 'order_time', 'status', 'cash_to_be_collected', 'cash_to_be_collected as amount', 'driver_cost', 'images_array as task_images', 'unique_id', 'call_back_url', 'actual_distance', 'actual_time','order_number');
    }

}
