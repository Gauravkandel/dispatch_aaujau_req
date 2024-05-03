<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagCustomer extends Model
{
    protected $fillable = [
        'customer_id', 'tag_id'
    ];
}