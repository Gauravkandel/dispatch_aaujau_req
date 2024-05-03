<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubClient extends Model
{
    protected $fillable = ['uid','name','email','phone_number','status'];

}
