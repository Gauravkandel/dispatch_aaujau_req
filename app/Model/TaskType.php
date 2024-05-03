<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    protected $table = 'task_types';
    protected $fillable = ['name', 'client_id'];




    public function getNameAttribute($value)
    {
        if($value == 'Drop-off')
        return 'Drop';
        else
        return $value;
    }
}
