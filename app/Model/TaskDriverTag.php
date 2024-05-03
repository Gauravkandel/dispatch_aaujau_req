<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskDriverTag extends Model
{
    protected $fillable = [
        'task_id'.'tag_id'
    ];
}

