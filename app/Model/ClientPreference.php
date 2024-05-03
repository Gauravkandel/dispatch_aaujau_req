<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    protected $fillable = [
        'client_id', 'theme', 'show_limited_address' ,'distance_unit', 'currency_id', 'language_id', 'agent_name', 'acknowledgement_type', 'date_format', 'time_format', 'map_type', 'map_key_1', 'map_key_2', 'sms_provider', 'sms_provider_key_1', 'sms_provider_key_2', 'allow_feedback_tracking_url', 'task_type', 'order_id', 'email_plan', 'domain_name', 'personal_access_token_v1', 'personal_access_token_v2','sms_provider_number','allow_all_location','fcm_server_key','customer_support','customer_support_key', 'customer_support_application_id','sms_credentials', 'verify_phone_for_driver_registration', 'is_edit_order_driver', 'is_cancel_order_driver', 'reffered_by_amount', 'reffered_to_amount','job_consist_of_pickup_or_delivery','maximum_route_per_job','create_batch_hours','is_driver_slot'];

    public function currency(){
        return $this->hasOne('App\Model\Currency','id','currency_id');
    }


}
