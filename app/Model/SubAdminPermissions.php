<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubAdminPermissions extends Model
{
    protected $fillable = ['sub_admin_id','permission_id'];


    public function permission(){
        return $this->belongsTo('App\Model\Permissions', 'permission_id', 'id')->select('*','name as name_code');
    }
}
