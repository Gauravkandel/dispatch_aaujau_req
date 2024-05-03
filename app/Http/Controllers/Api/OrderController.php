<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use Mail;
use Config;
use Validation;
use Carbon\Carbon;
use Kawankoding\Fcm\Fcm;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GClient;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Agent, Client, Customer, Geo, Location, Order, Roster, Task, TaskReject, Timezone, AllocationRule, ClientPreference, DriverGeo, NotificationEvent, NotificationType, SmtpDetail, PricingRule, TagsForAgent, TagsForTeam, Team, TaskProof, OrderCancelReason};

class OrderController extends BaseController
{
    use ApiResponser;

    public function createOrderCancelRequest(Request $request, $id){
        try{
            $user = Auth::user();
            $order = Order::where('id', $id)->where('status', '!=', 'completed')->where('driver_id', $user->id)->first();
            if(!$order){
                return $this->error(__('Invalid Data'), 422);
            }

            if(!empty($order->call_back_url)){
                if(strpos($order->call_back_url, 'dispatch-order-status-update') !== false){
                    $dispatch_order_cancel_url = str_replace('/dispatch-order-status-update/', '/dispatch-order-cancel-request/', $order->call_back_url);
                }else{
                    $dispatch_order_cancel_url = str_replace('/dispatch-pickup-delivery/', '/dispatch-order-cancel-request/', $order->call_back_url);
                }
                
                $client = new GClient(['content-type' => 'application/json']);
                $res = $client->get($dispatch_order_cancel_url.'?reject_reason='.urlencode($request->reject_reason));
                $response = json_decode($res->getBody(), true);
                
                if($response['status'] == 'Success'){
                    return $this->success('', $response['message']);
                }else{
                    return $this->error($response['message'], 422);
                }
            }else{
                return $this->error(__('Invalid Data'), 422);
            }
        }
        catch(\Exception $ex){
            return $this->error(__('Server Error'), $ex->getCode());
        }
    }

    public function getOrderCancelReasons(Request $request){
        $reasons = OrderCancelReason::where('status', 1)->get();
        return $this->success($reasons);
    }

    /**
     * Reschedule Order Timing and send Notifications to driver.
     * POST Route
     * Added By Ovi
     * @return \Illuminate\Http\Response
     */
    public function rescheduleOrder(Request $request)
    {

        // Get Client Timezone
        $auth =  Client::with(['getAllocation', 'getPreference'])->first();
        $tz = new Timezone();
        $auth->timezone = $tz->timezone_name($auth->timezone);
      
        if(!empty($request->schedule_dropoff))
        {
            // Convert Time To UTC
            // Get Dropoff Order
            $schedule_dropoff = Carbon::parse($request->schedule_dropoff . $auth->timezone ?? 'UTC')->tz('UTC');
            $dropoffOrder = Order::where('order_number', $request->order_number)->where('unique_id', $request->order_unique_id)->first();
            $dropoffOrder->order_time = $schedule_dropoff;
            $dropoffOrder->save();
        }

        if(!empty($request->schedule_pickup))
        {
            // Get Pickup Order
            // Convert Time To UTC
            $schedule_pickup  = Carbon::parse($request->schedule_pickup . $auth->timezone ?? 'UTC')->tz('UTC');
            $pickupOrder = Order::where('id', '<', $dropoffOrder->id)->where('order_number', $request->order_number)->orderBy('id','DESC')->first();
            $pickupOrder->order_time = $schedule_pickup;
            $pickupOrder->save();
        }

        $title = 'Schedule Timing Modified';
        $body  = 'The schedule timing of order number #'.$request->order_number.' has been modified by the customer.';

        // Send Dropoff Driver Notification
        if($dropoffOrder->agent != ""){
            if($dropoffOrder->driver_id != ""){
                $device_token = [
                    $dropoffOrder->agent->device_token
                ];
                $this->sendPushNotificationtoDriver($title, $body, $auth, $device_token, $dropoffOrder->call_back_url );
            }
        }elseif($pickupOrder->agent != ""){
            if($pickupOrder->driver_id != ""){
                $device_token = [
                    $pickupOrder->agent->device_token
                ];
                $this->sendPushNotificationtoDriver($title, $body, $auth, $device_token, $pickupOrder->call_back_url );
            }
        }
        
        return response()->json(['status' => 'success']);
    }

    public function seperate_connection($schemaName){
        $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => $schemaName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];

        Config::set("database.connections.$schemaName", $default);
    }

    public function sendPushNotificationtoDriver($title, $body, $auth, $device_token, $call_back_url)
    {
        $this->seperate_connection('db_'.$auth->database_name);   
        $client_preferences = DB::connection('db_'.$auth->database_name)->table('client_preferences')->where('client_id', $auth->code)->first();
        $fcm_server_key = !empty($client_preferences->fcm_server_key)? $client_preferences->fcm_server_key : config('laravel-fcm.server_key');
        $headers = [
                'Authorization: key=' . $fcm_server_key,
                'Content-Type: application/json',
        ];
        $data = [
            "registration_ids" => $device_token,
            "notification" => [
                'title' => $title,
                'body'  => $body,
                'sound' => "notification.wav",
                "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                'click_action' => $call_back_url,
                "android_channel_id" => "sound-channel-id"
            ],
            "data" => [
                'title' => $title,
                'body'  => $body,
                'data'  => $body,
                'type' => "order_modified"
            ],
            "priority" => "high"
        ];

        $dataString = $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
        $result = curl_exec($ch);
        Log::info($result);
        curl_close($ch);
        return true;
    }
}
