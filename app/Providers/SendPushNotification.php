<?php

namespace App\Providers;

use App\Providers\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPushNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Notification  $event
     * @return void
     */
    public function handle(Notification $event)
    {
        //
    }
}
