<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlockedToken extends Model
{

    protected $fillable = [
        'token', 'expired'
    ];
  
}