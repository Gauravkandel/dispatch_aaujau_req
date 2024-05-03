<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentsTag extends Model
{
    //

    public function agent(){
        return $this->belongsTo('App\Model\Agent');
    }
}
