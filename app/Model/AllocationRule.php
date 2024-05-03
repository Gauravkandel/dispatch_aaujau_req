<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AllocationRule extends Model
{
    protected $fillable = [
        'client_id', 'manual_allocation', 'auto_assign_logic', 'request_expiry', 'number_of_retries', 'start_radius', 'start_before_task_time', 'increment_radius', 'maximum_radius', 'maximum_batch_size', 'maximum_batch_count', 'maximum_task_per_person','self_assign',
        'maximum_cash_at_hand_per_person'];

}