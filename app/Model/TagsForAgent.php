<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagsForAgent extends Model
{
    protected $fillable = [
        'name'
    ];


    public function assignTags(){
        return $this->hasMany('App\Model\AgentsTag','tag_id','id');
    }
}