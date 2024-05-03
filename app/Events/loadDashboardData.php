<?php

namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Model\Client;
use App\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Log;

class loadDashboardData implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $orderid;
    public $order_status;
    public $order_date;
    public $agent_status;
    public $htmldata;
    public $channelname;
    public function __construct($orderdata)
    {
        $client_details    = Client::first();
        $request = new Request([
            'userstatus'   => !empty($orderdata->agent)?$orderdata->agent->is_available:2,
            'routedate' => date('Y-m-d', strtotime($orderdata->order_time)),
        ]);
        $this->channelname   = "orderdata".$client_details->code."".date('Y-m-d', strtotime($orderdata->order_time));
        $this->orderid       = $orderdata->id;
        $this->order_date    = $orderdata->order_time;
        $this->order_status  = $orderdata->status;
        $this->agent_status  = !empty($orderdata->agent)?$orderdata->agent->is_available:'';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [new Channel($this->channelname)];
    }

    
}
