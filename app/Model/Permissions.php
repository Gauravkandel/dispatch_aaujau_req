<?php


namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $fillable = [];


    public function getNameCodeAttribute($value)
    { 
        if(!empty($value)){
        $value = preg_replace("/[^a-zA-Z]+/", "", $this->attributes['name']);
        $value = strtolower($value);
        return $value;
      }
      return $value;
    }
}
