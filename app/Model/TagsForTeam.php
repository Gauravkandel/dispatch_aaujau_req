<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagsForTeam extends Model
{
    protected $fillable = [
        'name'
    ];


    public function assignTeams(){
        return $this->hasMany('App\Model\TeamTag','tag_id','id');
    }    

    
}