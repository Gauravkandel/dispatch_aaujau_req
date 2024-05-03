<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmtpDetail extends Model
{
    protected $fillable = ['client_id','driver','host','port','encryption','username','password','from_address'];
    
}
