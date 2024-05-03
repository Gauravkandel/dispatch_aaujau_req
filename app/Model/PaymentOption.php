<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    protected $fillable = ['code','path','title','credentials','status'];

    protected $appends = ['title_lng'];

    public function getTitleLngAttribute(){
        return __($this->title);
    }
}
