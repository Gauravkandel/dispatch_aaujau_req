<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name','amount','description'];
}
