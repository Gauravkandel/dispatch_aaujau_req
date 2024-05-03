<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LocationDistance extends Model
{
    protected $table = 'location_distance';
    protected $fillable = ['from_loc_id', 'to_loc_id','distance'];
}
