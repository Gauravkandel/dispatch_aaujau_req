<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskTeamTag extends Model
{
    protected $fillable = [
        'task_id','tag_id'
    ];


    public function tag(){
        return $this->belongsTo('App\Model\TagsForTeam','tag_id','id');
    }

}
