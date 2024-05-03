<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TeamTag extends Model
{
    public function team(){
        return $this->belongsTo('App\Model\Team');
    }
}
