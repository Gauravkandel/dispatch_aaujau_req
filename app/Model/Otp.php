<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['phone', 'opt', 'valid_till'];
}
