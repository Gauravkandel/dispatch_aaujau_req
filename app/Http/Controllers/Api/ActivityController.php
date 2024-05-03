<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{Agent, AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof, Timezone, User, PaymentOption};
use Validation;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Model\Roster;
use Config;
use Illuminate\Support\Facades\URL;
use GuzzleHttp\Client as GClient;

class ActivityController extends BaseController
{

    /**
     * Store/Update Client Preferences
     */
    public function clientPreferences()
    {
        $preferences = ClientPreference::with('currency')->where('id', 1)->first();

        $payment_codes = ['stripe'];
        $payment_creds = PaymentOption::select('code', 'credentials')->whereIn('code', $payment_codes)->where('status', 1)->get();
        if ($payment_creds) {
            foreach ($payment_creds as $creds) {
                $creds_arr = json_decode($creds->credentials);
                if ($creds->code == 'stripe') {
                    $preferences->stripe_publishable_key = (isset($creds_arr->publishable_key) && (!empty($creds_arr->publishable_key))) ? $creds_arr->publishable_key : '';
                }
            }
        }
        return response()->json([
            'message' => '',
            'data' => $preferences,
            'status' => 200
        ]);
    }

    /**
     * update driver availability status if 0 than 1 if 1 than 0

     */
    public function updateDriverStatus(Request $request)
    {
        $agent               = Agent::findOrFail(Auth::user()->id);
        $agent->is_available = ($agent->is_available == 1) ? 0 : 1;
        $agent->device_token = ((!empty($request->device_token) && $agent->is_available == 1) ? $request->device_token : '');
        $agent->update();

        // if driver is offline so do not send push notification-------------start--code---
        $schemaName = 'royodelivery_db';
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
        config(["database.connections.mysql.database" => $schemaName]);
        if($agent->is_available == 1){
            DB::connection($schemaName)->table('rosters')->where('created_at', '<', date('Y-m-d H:i:s'))->where(['driver_id'=>Auth::user()->id,'device_type'=>Auth::user()->device_type])->delete();
        }else{
            DB::connection($schemaName)->table('rosters')->where(['driver_id'=>Auth::user()->id,'device_type'=>Auth::user()->device_type])->delete();
        }
        DB::disconnect($schemaName);
        // if driver is offline so do not send push notification---------------end--code---

        return response()->json([
            'message' => __('Status updated Successfully'),
            'data' => array('is_available' => $agent->is_available),
            'status' => 200
        ]);
    }

    /**
     * Login user and create token
     *
     */
    public function tasks(Request $request)
    {
        $header = $request->header();
        $client_code = Client::where('database_name', $header['client'][0])->first();
        $tz = new Timezone();
        $client_code->timezone = $tz->timezone_name($client_code->timezone);
        $start     = Carbon::now($client_code->timezone ?? 'UTC')->startOfDay();
        $end       = Carbon::now($client_code->timezone ?? 'UTC')->endOfDay();
        $utc_start = Carbon::parse($start . $client_code->timezone ?? 'UTC')->tz('UTC');
        $utc_end   = Carbon::parse($end . $client_code->timezone ?? 'UTC')->tz('UTC');

        $id     = Auth::user()->id;

        $all    = $request->all;
        $tasks   = [];

        if ($all == 1) {
            $orders = Order::where('driver_id', $id)->where('status', 'assigned')->orderBy("order_time","ASC")->orderBy("id","ASC")->pluck('id')->toArray();
        } else {
            $orders = Order::where('driver_id', $id)->where('order_time', '>=', $utc_start)->where('order_time', '<=', $utc_end)->where('status', 'assigned')->orderBy("order_time","ASC")->orderBy("id","ASC")->pluck('id')->toArray();
        }


        if (count($orders) > 0) {
            $tasks = Task::whereIn('order_id', $orders)->where('task_status', '!=', 4)->Where('task_status', '!=', 5)
            ->with(['location','tasktype','order.customer','order.customer.resources','order.task.location'])->orderBy("order_id", "DESC")
            ->orderBy("id","ASC")
            ->get();
            if (count($tasks) > 0) {
                //sort according to task_order
                $tasks = $tasks->toArray();
                if ($tasks[0]['task_order'] !=0) {
                    usort($tasks, function ($a, $b) {
                        return $a['task_order'] <=> $b['task_order'];
                    });
                }
            }
        }

        return response()->json([
            'data' => $tasks,
            'status' => 200,
            'message' => __('success')
        ], 200);
    }

    /**
     * Login user and create token
     *

     */
    public function profile(Request $request)
    {
        $agent = Agent::where('id', Auth::user()->id)->first();

        return response()->json([
        'data' => $agent,
        'status' => 200,
        'message' => __('success')
       ], 200);
    }


    public function updateProfile(Request $request)
    {
        $saved = Agent::where('id', Auth::user()->id)->first();

        $header = $request->header();
        $client_code = Client::where('database_name', $header['client'][0])->first('code');
        $getFileName = '';
        // Handle File Upload
        if (isset($request->profile_picture)) {
            if ($request->hasFile('profile_picture')) {
                $folder = str_pad($client_code->code, 8, '0', STR_PAD_LEFT);
                $folder = 'client_'.$folder;
                $file = $request->file('profile_picture');
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                $s3filePath = '/assets/'.$folder.'/agents' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                $getFileName = $path;
            }
        } else {
            $getFileName = $saved->profile_picture;
        }

        $agent                   = Agent::find(Auth::user()->id);
        $agent->name             = isset($request->name)?$request->name:$saved->name;
        $agent->profile_picture  = $getFileName;
        $agent->vehicle_type_id  = $request->vehicle_type_id;
        $agent->make_model       = isset($request->make_model)?$request->make_model:$saved->make_model;
        $agent->plate_number     = isset($request->plate_number)?$request->plate_number:$saved->plate_number;
        $agent->phone_number     = isset($request->phone_number)?$request->phone_number:$saved->phone_number;
        $agent->color            = isset($request->color)?$request->color:$saved->color;

        if ($agent->save()) {
            return response()->json([
                'message' => __('Profile Updated Successfully'),
            ], 200);
        } else {
            return response()->json([
                'message' => __('Sorry Something Went Wrong'),
            ], 404);
        }
    }

    public function agentLog(Request $request)
    {
        $header = $request->header();
        $client_code = Client::where('database_name', $header['client'][0])->first();
        $preferences = ClientPreference::with('currency')->first();
        $tz = new Timezone();
        $client_code->timezone = $tz->timezone_name($client_code->timezone);
        $start     = Carbon::now($client_code->timezone ?? 'UTC')->startOfDay();
        $end       = Carbon::now($client_code->timezone ?? 'UTC')->endOfDay();
        $utc_start = Carbon::parse($start . $client_code->timezone ?? 'UTC')->tz('UTC');
        $utc_end   = Carbon::parse($end . $client_code->timezone ?? 'UTC')->tz('UTC');

        $tasks   = [];

        $data =  [
            'agent_id'          => Auth::user()->id,
            'lat'               => $request->lat,
            'long'              => $request->long,
            'battery_level'     => $request->battery_level,
            'os_version'        => $request->os_version,
            'app_version'       => $request->app_version,
            'current_speed'     => $request->current_speed,
            'on_route'          => $request->on_route,
            'device_type'       => ucwords($request->device_type),
            'heading_angle'     => $request->heading_angle ?? 0,
        ];

        if ($request->lat=="" || $request->lat==0 || $request->lat== '0.00000000') {
        } else {

            if(!empty($preferences->customer_notification_per_distance) && !empty($preferences->custom_mode)){
                
                //get details of customer notification per distance 
                $clientPreference = json_decode($preferences->customer_notification_per_distance);

                $configCustomerNotification = json_decode($preferences->custom_mode)->is_hide_customer_notification;

                //check is_send_customer_notification is on/not
                if(!empty($clientPreference->is_send_customer_notification) && ($clientPreference->is_send_customer_notification == 'on') && ($configCustomerNotification == 1)){
                    \Log::info('permission sucess');
                    //get agent orders 
                    $orders = Order::where('driver_id', Auth::user()->id)->where('status', 'assigned')->orderBy('order_time')->pluck('id')->toArray();
                    if (count($orders) > 0) {
                        \Log::info('get order');
                        
                        //get agent current task
                        $tasks = Task::whereIn('order_id', $orders)->where('task_status', 2)->with(['location','tasktype','order.customer'])->orderBy('order_id', 'desc')->orderBy('id', 'ASC')->get()->first();
                        if (!empty($tasks)) {

                            \Log::info('get tasks--');
                            \Log::info($tasks);
                            \Log::info('get tasks--');
                            $callBackUrl = str_ireplace('dispatch-pickup-delivery', 'dispatch/customer/distance/notification', $tasks->order->call_back_url);
                            $latitude    = [];
                            $longitude   = [];

                            \Log::info($callBackUrl);
                            // check task location in not empty and task created by custmer from order penel  
                            if(!empty($tasks->location) && !empty($callBackUrl)){
                                \Log::info('get task location');
                                $tasksLocationLat  = $tasks->location->latitude;
                                $tasksLocationLong = $tasks->location->longitude;
            
                                //get distance using lat-long
                                $getDistance = $this->getLatLongDistance($tasksLocationLat, $tasksLocationLong, $request->lat, $request->long, $clientPreference->distance_unit);
            
                                // insert agent coverd distance
                                $data['distance_covered'] = $getDistance;
                                $data['current_task_id'] = $tasks->id;
                                AgentLog::create($data);

                                // check notification send to customer pr km/miles
                                $agentDistanceCovered = AgentLog::where('current_task_id', $tasks->id)->where('distance_covered', 'LIKE', '%'.$getDistance.'%')->count();
                                
                                if($agentDistanceCovered == 1 && $getDistance > 0){
                                    \Log::info('in send notification');
                                    $notificationTitle       = $clientPreference->title;
                                    $notificationDiscription = str_ireplace("{distance}", $getDistance.' '.$clientPreference->distance_unit, $clientPreference->description);
                                    $notificationDiscription = str_ireplace("{co2_emission}", $clientPreference->co2_emission * $getDistance, $notificationDiscription);
                                    
                                    $postdata =  ['notificationTitle' => $notificationTitle, 'notificationDiscription' => $notificationDiscription];
            
                                    $client = new GClient(['content-type' => 'application/json']);
                                    
                                    $res = $client->post($callBackUrl,
                                        ['form_params' => ($postdata)]
                                    );
                                    $response = json_decode($res->getBody(), true);   
                                    \Log::info('responce');
                                    \Log::info($response);

                                }
                                
                            }
                        }
                    }                                           
                }
            }else{
                AgentLog::create($data);
                //event(new \App\Events\agentLogFetch());
            }
        }

        $id    = Auth::user()->id;
        $all   = $request->all;

        if ($all == 1) {
            $orders = Order::where('driver_id', $id)->where('status', 'assigned')->orderBy('order_time')->pluck('id')->toArray();
        } else {
            $orders = Order::where('driver_id', $id)->whereBetween('order_time',[$utc_start, $utc_end])->where('status', 'assigned')->orderBy('order_time')->pluck('id')->toArray();
        }

        $agent =  Agent::with('team')->where('id',$id)->first();
        $agent->device_type = $request->device_type??null;
        $agent->device_token = $request->device_token??null;;
        $agent->save();


        if (count($orders) > 0) {
            $tasks = Task::whereIn('order_id', $orders)->where('task_status', '!=', 4)->Where('task_status', '!=', 5)->with(['location','tasktype','order.customer'])->orderBy('order_id', 'desc')->orderBy('id', 'ASC')->get();
            if (count($tasks) > 0) {
                //sort according to task_order
                $tasks = $tasks->toArray();
                if ($tasks[0]['task_order'] !=0) {
                    usort($tasks, function ($a, $b) {
                        return $a['task_order'] <=> $b['task_order'];
                    });
                }
            }
        }

        $agents    = $agent; //Agent::where('id', $id)->with('team')->first();
        $taskProof = TaskProof::all();

        $payment_codes = ['stripe'];
        $payment_creds = PaymentOption::select('code', 'credentials')->whereIn('code', $payment_codes)->where('status', 1)->get();
        if ($payment_creds) {
            foreach ($payment_creds as $creds) {
                $creds_arr = json_decode($creds->credentials);
                if ($creds->code == 'stripe') {
                    $preferences->stripe_publishable_key = (isset($creds_arr->publishable_key) && (!empty($creds_arr->publishable_key))) ? $creds_arr->publishable_key : '';
                }
            }
        }

        $allcation = AllocationRule::first('request_expiry');

        $preferences['alert_dismiss_time'] = (int)$allcation->request_expiry;
        $agents['client_preference']  = $preferences;
        $agents['task_proof']         = $taskProof;
        $datas['user']                = $agents;
        $datas['tasks']               = $tasks;

        return response()->json([
            'data' => $datas,
            'status' => 200,
            'message' => __('success')
        ], 200);
    }

    function getLatLongDistance($lat1, $lon1, $lat2, $lon2, $unit) {

        $earthRadius = 6371;  // earth radius in km

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $final = round($angle * $earthRadius);
        if ($unit == "km") {
            return $final;
        } else {
            return round($final * 0.6214);
        }
    }

    public function cmsData(Request $request)
    {
        $data = Cms::where('id', $request->cms_id)->first();

        return response()->json([
            'data' => $data,
            'status' => 200,
            'message' => __('success')
           ], 200);
    }


    public function taskHistory(Request $request)
    {
        $id    = Auth::user()->id;
        if(!empty($request->from_date) && !empty($request->to_date)){
            $orders = Order::where('driver_id', $id)->whereBetween('order_time', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"])->pluck('id')->toArray();
        }else{
            $orders = Order::where('driver_id', $id)->pluck('id')->toArray();
        }
        if (isset($orders)) {
            $tasks = Task::with(['location','tasktype','order.customer','order.task.location'])
            ->whereIn('order_id', $orders)
            ->where(function($q){
                $q->whereIn('task_status', [4,5])
                ->orWhereHas('order', function($q1){
                    $q1->where('status', 'cancelled');
                });
            })
            ->orderBy('order_id', 'DESC')
            ->get(['id','order_id','dependent_task_id','task_type_id','location_id','appointment_duration','task_status','allocation_type','created_at','barcode']);

            $driverearning = 0;
            $previousorder = 0;
            foreach($tasks as $task){
                if(!empty($task->order->driver_cost) && ($previousorder != $task->order_id) && $task->order->status !='cancelled'){
                    $driverearning += $task->order->driver_cost;
                    $previousorder = $task->order_id;
                }
            }
        } else {
            $task = [];
        }

        return response()->json([
            'data' => array('tasks' =>$tasks, 'totalCashCollected'=>$driverearning),
            'status' => 200,
            'message' => __('success')
        ], 200);
    }
}
