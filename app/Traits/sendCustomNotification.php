<?php
namespace App\Traits;
use DB, Log;
use Illuminate\Support\Collection;
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout};
use Kawankoding\Fcm\Fcm;

trait sendCustomNotification{

    //------------------------------Function created by surendra singh--------------------------//
    public function sendnotification($data, $client_preferences)
    {
        $new = [];

        array_push($new, $data['device_token']);
        Log::info($data);
        if(isset($new)){
            $fcm_server_key = !empty($client_preferences->fcm_server_key)? $client_preferences->fcm_server_key : 'null';

            $fcmObj = new Fcm($fcm_server_key);
            $fcm_store = $fcmObj->to($new) // $recipients must an array
                            ->priority('high')
                            ->timeToLive(0)
                            ->data($data)
                            ->notification([
                                'title'              => (empty($data['title']))?'Pickup Request':$data['title'],
                                'body'               => (empty($data['body']))?'Check All Details For This Request In App':$data['body'],
                                'sound'              => 'notification.mp3',
                                'android_channel_id' => 'Royo-Delivery',
                                'soundPlay'          => true,
                                'show_in_foreground' => true,
                            ])
                            ->send();
            
        }
    }
    //-------------------------------------------------------------------------------------------//
    

}
