<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RosterDetail extends Model
{
    protected $table = 'roster_details';
    protected $fillable = ['customer_name','customer_phone_number','short_name','address','lat','long','task_count','unique_id'];
}
