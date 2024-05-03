<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmsProvider extends Model
{
    protected $fillable = ['provider', 'keyword', 'status'];
}
