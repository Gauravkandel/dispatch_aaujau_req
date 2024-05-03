<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Wallet extends Model
{
    // protected $fillable = ['holder_type','holder_id','name','slug','description','meta','balance','decimal_places'];
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
