<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable =['name'];

    /**
     * Get NotificationEvents
    */
    public function notification_events()
    {
      return $this->hasMany('App\Model\NotificationEvent');
    }
}
