<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Task extends Model
{
    protected $fillable = [
        'order_id', 'dependent_task_id', 'task_type_id', 'location_id', 'appointment_duration', 'pricing_rule_id', 'distance', 'assigned_time', 'accepted_time', 'declined_time', 'started_time', 'reached_time', 'failed_time', 'cancelled_time', 'cancelled_by_admin_id', 'Completed_time', 'allocation_type', 'task_status',
        'created_at', 'note', 'proof_image', 'proof_signature', 'barcode', 'quantity', 'alcoholic_item'
    ];

    public function order()
    {
        return $this->belongsTo('App\Model\Order', 'order_id', 'id')->select('id', 'customer_id', 'driver_id', 'recipient_phone', 'Recipient_email', 'task_description', 'auto_alloction', 'order_time', 'status', 'cash_to_be_collected', 'cash_to_be_collected as amount', 'driver_cost', 'images_array as task_images', 'unique_id', 'call_back_url', 'actual_distance', 'actual_time','is_restricted', 'vendor_id', 'order_vendor_id', 'sync_order_id','order_number','dbname'); 
    }

    public function location()
    {
        return $this->belongsTo('App\Model\Location', 'location_id', 'id');
    }

    public function tasktype()
    {
        return $this->belongsTo('App\Model\TaskType', 'task_type_id', 'id')->select('id', 'name');
    }

    public function pricing()
    {
        return $this->belongsTo('App\Model\PricingRule', 'pricing_rule_id', 'id');
    }


    /*public function teamtags(){
        return $this->belongsToMany('App\Model\TaskTeamTag', 'task_team_tags','task_id','tag_id');
    }
    public function drivertags(){
        return $this->belongsToMany('App\Model\TaskDriverTag', 'task_driver_tags','task_id','tag_id');
    }*/

    public function getProofImageAttribute($value)
    {
        if (!empty($value)) {
            $value = Storage::disk('s3')->url($value);
        }
        return $value;
    }

    public function getProofSignatureAttribute($value)
    {
        if (!empty($value)) {
            $value = Storage::disk('s3')->url($value);
        }
        return $value;
    }

    public function getStatusAttribute()
    {
        $dispatcher_status_option = $this->attributes['task_status'];
        $type = $this->attributes['task_type_id'];

        $status_data = '';

        //$dispatcher_status_option  =  $dispatcher_status_option-1;
        //return $dispatcher_status_option;
        switch ($dispatcher_status_option) {
            case 1:
                if ($type == '1') {
                    $status_data = __(getAgentNomenclature().' assigned');
                } else {
                    $status_data = __('Pending');
                }
                break;
            case 2:
                if ($type == '1') {
                    $status_data = __('On the way');
                } else {
                    $status_data = __('On the way');
                }
                break;
            case 3:
                if ($type == '1') {
                    $status_data = __('Ready for pickup');
                } else {
                    $status_data = __('Ready for departure');
                }
                break;
            case 4:
                $status_data = __('Completed');
                break;
            case 5:
                $status_data = __('Canceled');
                break;
            default:
                $status_data = '';
        }

        return $status_data;
    }
}
