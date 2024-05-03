<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentLog extends Model
{
    protected $fillable = ['agent_id','current_task_id', 'lat', 'long', 'battery_level', 'os_version','app_version','current_speed','on_route','device_type', 'heading_angle', 'distance_covered'];
}
