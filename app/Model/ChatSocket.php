<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatSocket extends Model
{
    use SoftDeletes;
    protected $fillable = [
      'title','domain_url', 'status'
    ];
}
