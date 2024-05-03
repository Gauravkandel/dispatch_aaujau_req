<?php

namespace App\Http\Controllers\Api;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Traits\ChatTrait;

class ChatControllerOrderNotification extends BaseController
{
    use ApiResponser;
    use ChatTrait;

    public $client_data;    
    /**
     * sendNotificationToAgent
     *
     * @param  mixed $request
     * @return void
     */
    public function sendNotificationToAgent(Request $request){
     
        try {
            $notiFY = $this->sendNotification_to_agent($request);
            return response()->json([ 'notiFY'=>$notiFY , 'status' => 200, 'message' => __('sent22!!!')]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found !!!')]);
        }

    }

  
}
