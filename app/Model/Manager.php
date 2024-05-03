<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $fillable = [
        'client_id','name','password','email','phone_number','profile_picture','can_create_task','can_edit_task_created','can_edit_all', 'can_manage_unassigned_tasks' ,'can_edit_auto_allocation' 
    ];

    protected $attributes = [
        'password' => ''
    ];
}
