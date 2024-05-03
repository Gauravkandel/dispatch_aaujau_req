<?php

namespace App\Http\Controllers;

use Config;
use Storage;
use Carbon\Carbon;
use App\Model\{Client, Order,DriverRegistrationDocument};
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\sendCustomNotification;

class TrackingController extends Controller
{
    use sendCustomNotification;

    public function OrderTracking($domain = '', $user, $id)
    {
        $respnse = $this->connection($user);

        if ($respnse['status'] == 'connected') {
            $order   = DB::connection($respnse['database'])->table('orders')->where('unique_id', $id)->leftJoin('agents', 'orders.driver_id', '=', 'agents.id')
                ->select('orders.*', 'agents.name', 'agents.profile_picture', 'agents.phone_number')->first();
            if (isset($order->id)) {
                $tasks = DB::connection($respnse['database'])->table('tasks')->where('order_id', $order->id)->leftJoin('locations', 'tasks.location_id', '=', 'locations.id')
                    ->select('tasks.*', 'locations.latitude', 'locations.longitude', 'locations.short_name', 'locations.address')->orderBy('task_order')->get();
                $orderc = DB::connection($respnse['database'])->table('orders')->where('id', $order->id)->where('status','completed')->count();
                if($orderc == 0)
                $agent_location = DB::connection($respnse['database'])->table('agent_logs')->where('agent_id', $order->driver_id)->latest()->first();
                else{
                    $agent_location = [];
                    $lastElement = $tasks->last();
                    $agent_location['lat']  = $lastElement->latitude;
                    $agent_location['lng']  = $lastElement->longitude;
                }
                $map_key = DB::connection($respnse['database'])->table('client_preferences')->select('map_key_1')->latest()->first();
                
                $mapkey = $map_key->map_key_1 ?? '';

                return view('tracking/tracking', compact('tasks', 'order', 'agent_location','mapkey'));
            } else {
                return view('tracking/order_not_found');
            }
        } else {
            return view('tracking/order_not_found');
        }
    }

    public function OrderFeedback($domain = '', $user, $id)
    {
        $respnse = $this->connection($user);

        if ($respnse['status'] == 'connected') {
            $order   = DB::connection($respnse['database'])->table('orders')->where('unique_id', $id)->first();

            if (isset($order->id)) {
                return view('tracking/feedback', compact('user', 'id'));
            } else {
                return view('tracking/order_not_found');
            }
        } else {
            return view('tracking/order_not_found');
        }
    }


    public function SaveFeedback(Request $request, $domain = '')
    {
        $respnse = $this->connection($request->client_code);

        if ($respnse['status'] == 'connected') {
            $order   = DB::connection($respnse['database'])->table('orders')->where('unique_id', $request->unique_id)->first();

            if (isset($order->id)) {
                $check_alredy  = DB::connection($respnse['database'])->table('order_ratings')->where('order_id', $order->id)->first();

                if (isset($check_alredy->id)) {
                    return response()->json(['status' => true, 'message' => 'Feedback has been already submitted.']);
                } else {
                    $data = [
                        'order_id'    => $order->id,
                        'rating'      => $request->rating,
                        'review'      => $request->review,
                    ];

                    DB::connection($respnse['database'])->table('order_ratings')->insert($data);

                    return response()->json(['status' => true, 'message' => 'Your feedback is submitted']);
                }
            } else {
                return response()->json(['status' => true, 'message' => 'Order Not Found']);
            }
        } else {
            return response()->json(['status' => true, 'message' => 'Sorry Wrong Url']);
        }
    }


    public function connection($user)
    {
        $client = Client::where('code', $user)->first();

        if (isset($client->database_name)) {
            $database_name = 'db_' . $client->database_name;

            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $database_name,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];

            Config::set("database.connections.$database_name", $default);

            return  $respnse = ['status' => 'connected', 'database' => $database_name];
        } else {
            return  $respnse = ['status' => 'failed'];
        }
    }


    public function OrderTrackingDetail($domain = '', $user, $id)
    {
        $respnse = $this->connection($user);
        $total_order_by_agent = 0;
        $avgrating = 0;
        if ($respnse['status'] == 'connected') {
            $order = DB::connection($respnse['database'])->table('orders')->where('unique_id', $id)->leftJoin('agents', 'orders.driver_id', '=', 'agents.id')
                ->select('orders.*', 'agents.name','agents.name','agents.color','agents.plate_number', 'agents.profile_picture', 'agents.phone_number','agents.vehicle_type_id')->first();
            if (isset($order->id)) {
                $tasks = DB::connection($respnse['database'])->table('tasks')->where('order_id', $order->id)->leftJoin('locations', 'tasks.location_id', '=', 'locations.id')
                    ->select('tasks.*', 'locations.latitude', 'locations.longitude', 'locations.short_name', 'locations.address')->orderBy('task_order')->get();
                $orderc = DB::connection($respnse['database'])->table('orders')->where('id', $order->id)->where('status','completed')->count();
                if($orderc == 0)
                $agent_location = DB::connection($respnse['database'])->table('agent_logs')->where('agent_id', $order->driver_id)->latest()->first();
                else{
                    $agent_location = [];
                    $lastElement = $tasks->last();
                    $agent_location['lat']  = $lastElement->latitude;
                    $agent_location['lng']  = $lastElement->longitude;
                }

                if($order->driver_id > 0){
                    $total_orders = DB::connection($respnse['database'])->table('orders')->where('driver_id',$order->driver_id)->pluck('id');
                    $total_order_by_agent = count($total_orders);
                    $avgrating = DB::connection($respnse['database'])->table('order_ratings')->whereIn('order_id',$total_orders)->sum('rating');
                    if($avgrating != 0)
                    $avgrating = $avgrating/$avgrating;
                    
                    $driver_document = DB::connection($respnse['database'])->table('driver_registration_documents')
                                            ->join('agent_docs','driver_registration_documents.name','=', 'agent_docs.label_name')
                                            ->select('agent_docs.*','driver_registration_documents.file_type','driver_registration_documents.name')
                                            ->where('agent_docs.agent_id',$order->driver_id)->get();
                    $order->driver_document =$driver_document;        
                }

                $img = 'https://imgproxy.royodispatch.com/insecure/fit/300/100/sm/0/plain/' . Storage::disk('s3')->url($order->profile_picture ?? 'assets/client_00000051/agents605b6deb82d1b.png/XY5GF0B3rXvZlucZMiRQjGBQaWSFhcaIpIM5Jzlv.jpg');
                $base_url = 'https://royodelivery-assets.s3.us-west-2.amazonaws.com';

                $db_name = DB::connection($respnse['database'])->table('clients')->select('database_name')->first()->database_name;
                return response()->json([
                    'message' => 'Successfully',
                    'tasks' => $tasks,
                    'order'  => $order,
                    'agent_image' => $img, 
                    'agent_location'  => $agent_location,
                    'total_order_by_agent'  => $total_order_by_agent,
                    'avgrating'  => $avgrating,
                    'base_url' => $base_url,
                    'agent_dbname'  => $db_name
                ], 200);

                return view('tracking/tracking', compact('tasks', 'order', 'agent_location'));
            } else {

                return response()->json([
                    'message' => 'Error'], 400);
                return view('tracking/order_not_found');
            }
        } else {
            return response()->json([
                'message' => 'Error'], 400);
            return view('tracking/order_not_found');
        }
    }

    public function DriverRating($domain = '', $user, $id, Request $request)
    {
        //dd('sdfsdf');
        // $domain = '', $user, $id
        $respnse = $this->connection($user);
        if ($respnse['status'] == 'connected') {
            $order = DB::connection($respnse['database'])->table('orders')->where('unique_id', $id)->first();            

            if (isset($order->id)) {
                $check_alredy  = DB::connection($respnse['database'])->table('driver_ratings')->where('order_id', $order->id)->first();

                $data = [
                    'order_id'    => $order->id,
                    'driver_id'   => $order->driver_id,
                    'rating'      => $request->rating,
                    'review'      => $request->review,
                ];
                if (isset($check_alredy->id)) {
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    DB::connection($respnse['database'])->table('driver_ratings')->where('id',$check_alredy->id)->update($data);
                    return response()->json(['status' => true, 'message' => __('Rating has been updated')]);
                } else {
                    $data['created_at'] = date('Y-m-d H:i:s');

                    DB::connection($respnse['database'])->table('driver_ratings')->insert($data);

                    return response()->json(['status' => true, 'message' => __('Your rating is submitted')]);
                }
            } else {
                return response()->json(['status' => true, 'message' => __('Order Not Found')]);
            }
        } else {
            return response()->json([
                'message' => 'Error'], 400);
            // return view('tracking/order_not_found');
        }

    }



    # order cancel section 
    public function orderCancelFromOrder(Request $request, $domain = '', $user, $id)
    {
        $respnse = $this->connection($user);
        if ($respnse['status'] == 'connected') {
            DB::connection($respnse['database'])->beginTransaction();
            try{
                $database_name = $respnse['database'];
                $order = DB::connection($respnse['database'])->table('orders')->where('unique_id', $id)->first();
                if (isset($order->id)) {
                    $orderc = DB::connection($respnse['database'])->table('orders')->where('id', $order->id)->update(['status' => 'cancelled', 'note'=>$request->reject_reason]);

                    $data = [
                        'order_id'          => $order->id,
                        'driver_id'         => $order->driver_id,
                        'status'            => 2,
                        'created_at'        => Carbon::now()->toDateTimeString(),
                        'updated_at'        => Carbon::now()->toDateTimeString(),
                    ];
                    $order_reject = DB::connection($respnse['database'])->table('task_rejects')->insert($data);

                    if(!empty($order->driver_id)){
                        $client_preferences = DB::connection($respnse['database'])->table('client_preferences')->first();
                        $oneagent = DB::connection($respnse['database'])->table('agents')->where('id', $order->driver_id)->first();
                        $notificationdata = [
                            'order_id'            => $order->id,
                            'batch_no'            => '',
                            'driver_id'           => $order->driver_id,
                            'notification_time'   => Carbon::now()->addSeconds(2)->format('Y-m-d H:i:s'),
                            'notificationType'    => 'CANCELLED',
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $oneagent->device_type,
                            'device_token'        => $oneagent->device_token,
                            'detail_id'           => rand(11111111, 99999999),
                            'title'               => 'Pickup Request Cancelled',
                            'body'                => 'Check All Details For This Request In App',
                        ];
                        $this->sendnotification($notificationdata, $client_preferences);
                    }
                    
                    DB::connection($respnse['database'])->commit();
                    return response()->json([
                        'status' => 'Success',
                        'message' => 'Order cancelled successfully',
                        // 'order'  => $order
                    ], 200);
                }
                else {
                    DB::connection($respnse['database'])->rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Invalid Data'], 400);
                }
            }
            catch(\Exception $ex){
                DB::connection($respnse['database'])->rollback();
                return response()->json(['status' => 'Error', 'message' => 'Server Error'], 400);
            }
        }else {
            return response()->json(['status' => 'Error', 'message' => 'Connection Error'], 400);
        }
    }


   
}
