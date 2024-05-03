<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class csvOrderImport extends Model
{
    protected $table = 'csv_orders_imports';
    protected $appends = ['storage_url'];
    public function getStorageUrlAttribute($value){
        return Storage::url('app/public/routes/'.$this->name);
    }
}
