<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskProof extends Model
{
    protected $fillable = ['image','image_requried','signature','signature_requried','note','note_requried','barcode','barcode_requried'];
}
