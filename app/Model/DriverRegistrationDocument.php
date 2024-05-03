<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverRegistrationDocument extends Model
{
    //
    protected $fillable=['file_type','name'];

    public function driver_document(){
        return $this->hasOne('App\Model\AgentDocs','label_name','name');
    }
}
