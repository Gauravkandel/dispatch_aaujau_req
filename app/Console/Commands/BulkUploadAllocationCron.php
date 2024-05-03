<?php
namespace App\Http\Controllers;

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Model\Task;
use App\Model\Location;
use App\Model\Customer;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;
use App\Model\TaskDriverTag;
use App\Model\TaskTeamTag;
use Illuminate\Http\Request;
use App\Model\Agent;
use App\Model\AllocationRule;
use App\Model\Client;
use App\Model\ClientPreference;
use App\Model\DriverGeo;
use App\Model\PricingRule;
use App\Model\Roster;
use App\Model\TaskProof;
use App\Model\category;
use App\Model\Geo;
use App\Model\Order;
use App\Model\order_category;
use App\Model\Timezone;
use App\Model\AgentLog;
use App\Model\{Team,TeamTag, csvOrderImport};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\RosterCreate;
use App\Models\RosterDetail;
use Illuminate\Support\Arr;
use App\Jobs\scheduleNotification;
use Log;
use DataTables;
use DB;
use Config;
use Illuminate\Support\Str;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\RoutesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrderImport;
use Illuminate\Foundation\Bus\Dispatchable;


class BulkUploadAllocationCron extends Command
{
    use Dispatchable;
    //use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BulkUploadAllocation:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $clients = Client::where('status', 1)->get();
            foreach ($clients as $key => $client) {
                $database_name = 'db_' .$client->database_name;
                $default = [
                    'driver'         => env('DB_CONNECTION', 'mysql'),
                    'host'           => env('DB_HOST'),
                    'port'           => env('DB_PORT'),
                    'database'       => $database_name,
                    'username'       => env('DB_USERNAME'),
                    'password'       => env('DB_PASSWORD'),
                    'charset'        => 'utf8mb4',
                    'collation'      => 'utf8mb4_unicode_ci',
                    'prefix'         => '',
                    'prefix_indexes' => true,
                    'strict'         => false,
                    'engine'         => null
                ];
                Config::set("database.connections.$database_name", $default);

                $auth =  DB::connection($database_name)->table('clients')->first();
                $tz = new Timezone();
                $timezone = $tz->timezone_name($auth->timezone);
                $now = Carbon::now();
                $date = Carbon::create($now, $timezone)->toDateString();
                $time = Carbon::create($now, $timezone)->toTimeString();

                $order_details = DB::connection($database_name)->table('orders')->where('status', '=', 'unassigned')
                ->where(function($query) use ($date, $time) {
                    $query->whereDate('order_time', '<', $date)
                    ->orWhere(function($q) use ($date, $time) {
                        $q->whereDate('order_time', '=', $date)
                        ->whereTime('order_time', '<=', $time);
                    });
                })->get();
                
                $allocation = DB::connection($database_name)->table('allocation_rules')->where('id', 1)->first();
                foreach($order_details as $order_detail){
                    $finalLocation = [];
                    $latitude  = [];
                    $longitude = [];
                    $taskcount = 0;
                    $send_loc_id = 0;
                    $taskdata = DB::connection($database_name)->table('tasks')->where('order_id', $order_detail->id)->get();
                    foreach ($taskdata as $task_details) {
                        $taskcount++;
                        $location = DB::connection($database_name)->table('locations')->where('id', $task_details->location_id)->first();
                        if ($taskcount == 1) {
                            $finalLocation = $location;
                            $send_loc_id = $location->id;
                        }
                        array_push($latitude, $location->latitude);
                        array_push($longitude, $location->longitude);
                    }

                    $geo = $this->createRoster($send_loc_id, $database_name);

                    $customer = DB::connection($database_name)->table('customers')->where('id', $order_detail->customer_id)->first();
                    
                    $notification_time = Carbon::now()->toDateTimeString();
                    $agent_id = NULL;
                    
                    if ($order_detail->auto_alloction === 'a' || $order_detail->auto_alloction === 'm') {
                        $this->SendToAll($geo, $notification_time, $agent_id, $order_detail->id, $customer, $finalLocation, $taskcount, $allocation, $database_name, $auth->code);    
                    }
                }
                \DB::disconnect($database_name);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 0;
    }



    public function finalRoster($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation, $database_name)
    {
        $allcation_type = 'AR';
        $date = \Carbon\Carbon::today();
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getPreference->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime); //this function is check the time diffrence and give the notification time
        $randem     = rand(11111111, 99999999);
        $rostersbeforetime       = $this->checkBeforeTimeDiffrence($notification_time, $beforetime);

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        } elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        } else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'                => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            if(!empty($oneagent->device_token) && $oneagent->is_available == 1){
                $data = [
                    'order_id'            => $orders_id,
                    'driver_id'           => $agent_id,
                    'notification_time'   => $time,
                    'notification_befor_time'   => $rostersbeforetime,

                    'type'                => $allcation_type,
                    'client_code'         => Auth::user()->code,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => $randem,
                ];
                // Log::info('finalRoster-single');
                // Log::info($data);
                // Log::info('finalRoster-single');
                $this->dispatch(new RosterCreate($data, $extraData)); //this job is for create roster in main database for send the notification  in manual alloction
            }
        } else {
            $unit              = $auth->getPreference->distance_unit;
            $try               = $auth->getAllocation->number_of_retries;
            $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person??0;
            $max_redius        = $auth->getAllocation->maximum_radius;
            $max_task          = $auth->getAllocation->maximum_batch_size;

            $dummyentry = [];
            $all        = [];
            $extra      = [];
            $remening   = [];

            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function ($o) use ($cash_at_hand, $date) {
                    $o->orderBy('id', 'DESC')->with(['logs','order'=> function ($f) use ($date) {
                        $f->whereDate('order_time', $date)->with('task');
                    }]);
                }])->get();

            $totalcount = $getgeo->count();
            $orders = order::where('driver_id', '!=', null)->whereDate('created_at', $date)->groupBy('driver_id')->get('driver_id');

            $allreadytaken = [];
            foreach ($orders as $ids) {
                array_push($allreadytaken, $ids->driver_id);
            }
            $counter = 0;
            $data = [];
            for ($i = 0; $i <= $try-1; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {
                    if (in_array($geoitem->driver_id, $allreadytaken) && !empty($geoitem->agent->device_token) && $geoitem->agent->is_available == 1) {
                        $extra = [
                            'id' => $geoitem->driver_id,
                            'device_type' => $geoitem->agent->device_type, 'device_token' => $geoitem->agent->device_token
                        ];
                        array_push($remening, $extra);
                    } else {
                        if(!empty($geoitem->agent->device_token) && $geoitem->agent->is_available == 1){
                            $data = [
                                'order_id'            => $orders_id,
                                'driver_id'           => $geoitem->driver_id,
                                'notification_time'   => $time,
                                'notification_befor_time' => $rostersbeforetime,
                                'type'                => $allcation_type,
                                'client_code'         => Auth::user()->code,
                                'created_at'          => Carbon::now()->toDateTimeString(),
                                'updated_at'          => Carbon::now()->toDateTimeString(),
                                'device_type'         => $geoitem->agent->device_type??null,
                                'device_token'        => $geoitem->agent->device_token??null,
                                'detail_id'           => $randem,
                            ];
                            if (count($dummyentry) < 1) {
                                array_push($dummyentry, $data);
                            }

                            //here i am seting the time diffrence for every notification

                            $time = Carbon::parse($time)
                                ->addSeconds($expriedate + 3)
                                ->format('Y-m-d H:i:s');
                            $rostersbeforetime = Carbon::parse($rostersbeforetime)
                                ->addSeconds($expriedate + 3)
                                ->format('Y-m-d H:i:s');
                            array_push($all, $data);
                        }
                        $counter++;
                    }

                    if ($allcation_type == 'N' && 'ACK' && count($all) > 0) {
                        Order::where('id', $orders_id)->update(['driver_id'=>$geoitem->driver_id]);

                        break;
                    }
                }

                foreach ($remening as $key =>  $rem) {
                    $data = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $rem['id'],
                        'notification_time'   => $time,
                        'notification_befor_time' => $rostersbeforetime,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $rem['device_type'],
                        'device_token'        => $rem['device_token'],
                        'detail_id'           => $randem,
                    ];

                    $time = Carbon::parse($time)
                        ->addSeconds($expriedate + 3)
                        ->format('Y-m-d H:i:s');
                    $rostersbeforetime = Carbon::parse($rostersbeforetime)
                        ->addSeconds($expriedate + 3)
                        ->format('Y-m-d H:i:s');

                    if (count($dummyentry) < 1) {
                        array_push($dummyentry, $data);
                    }
                    array_push($all, $data);
                    if ($allcation_type == 'N' && 'ACK' && count($all) > 0) {
                        Order::where('id', $orders_id)->update(['driver_id'=>$remening[$i]['id']]);

                        break;
                    }
                }
                $remening = [];
                if ($allcation_type == 'N' && 'ACK' && count($all) > 0) {
                    break;
                }
            }
            // Log::info('finalRoster-all');
            // Log::info($all);
            // Log::info('finalRoster-all');
            $this->dispatch(new RosterCreate($all, $extraData)); // //this job is for create roster in main database for send the notification  in auto alloction
        }
    }


    public function checkBeforeTimeDiffrence($notification_time, $beforetime)
    {
        $to   = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
        $from = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::parse($notification_time)->format('Y-m-d H:i:s'));
        $diff_in_minutes = $to->diffInMinutes($from);
        if ($diff_in_minutes < $beforetime) {
            return  Carbon::now()->toDateTimeString();
        } else {
            return Carbon::parse($notification_time)->subMinutes($beforetime);

        }
    }
    public function SendToAll($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation, $database_name, $clientcode)
    {
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = DB::connection($database_name)->table('clients')->where('code', $clientcode)->first();
        $getAllocation     = DB::connection($database_name)->table('allocation_rules')->where('client_id', $clientcode)->first();
        $getPreference     = DB::connection($database_name)->table('client_preferences')->where('client_id', $clientcode)->first();
        $expriedate        = (int)$getAllocation->request_expiry;
        $beforetime        = (int)$getAllocation->start_before_task_time;
        $maxsize           = (int)$getAllocation->maximum_batch_size;
        $type              = $getPreference->acknowledgement_type;
        $unit              = $getPreference->distance_unit;
        $try               = $getAllocation->number_of_retries;
        $cash_at_hand      = $getAllocation->maximum_cash_at_hand_per_person??0;
        $max_redius        = $getAllocation->maximum_radius;
        $max_task          = $getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $rostersbeforetime = $this->checkBeforeTimeDiffrence($notification_time, $beforetime);
        $order_details = DB::connection($database_name)->table('orders')->find($orders_id);
        $data = [];

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        } elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        } else {
            $allcation_type = 'N';
        }
       
        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'               => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            Log::info('innergeoty');
            $oneagent = Agent::where('id', $agent_id)->first();
            if(!empty($oneagent->device_token) && $oneagent->is_available == 1){
                $data = [
                    'order_id'            => $orders_id,
                    'driver_id'           => $agent_id,
                    'notification_time'   => $time,
                    'notification_befor_time' => $rostersbeforetime,
                    'type'                => $allcation_type,
                    'client_code'         => $clientcode,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => $randem,
                    'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                ];
                Log::info("if case RosterCreate send to all ");
                RosterCreate::dispatch($data, $extraData);
            }
        } else {
            //$getgeo = DB::connection($database_name)->table('driver_geos')->where('geo_id', $geo)->get();
            $getgeo = DB::connection($database_name)->table('driver_geos')
            ->select('driver_geos.driver_id as driId', 'agents.*', 'agent_logs.*', 'ord.num_order')
            ->join('agents','driver_geos.driver_id','agents.id')
            ->join('agent_logs','agent_logs.agent_id','agents.id')
            ->leftjoin(DB::raw('(SELECT COUNT(id) num_order, driver_id FROM `orders` WHERE DATE(order_time) = "'.$date.'" GROUP BY driver_id  ) ord'), function($join){
                $join->on('agents.id', '=', 'ord.driver_id');

            })
            ->groupBy('agent_logs.agent_id')
            ->get();Log::info("Datas");
            for ($i = 0; $i <= $try-1; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {
                    $datas = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $geoitem->driId,
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => $clientcode,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $geoitem->device_type,
                        'device_token'        => $geoitem->device_token,
                        'detail_id'           => $randem,
                        'cash_to_be_collected'=> $order_details->cash_to_be_collected??null,
                        ];
                    Log::info($datas);
                    array_push($data, $datas);
                    if ($allcation_type == 'N' && 'ACK') {Log::info('break');
                        DB::connection($database_name)->table('orders')->where('id', $orders_id)->update(['driver_id'=>$geoitem->driver_id]);
                        break;
                    }
                }
                $time = Carbon::parse($time)
                        ->addSeconds($expriedate + 10)
                        ->format('Y-m-d H:i:s');
                $rostersbeforetime = Carbon::parse($rostersbeforetime)
                        ->addSeconds($expriedate + 10)
                        ->format('Y-m-d H:i:s');
                if ($allcation_type == 'N' && 'ACK') {
                    Log::info('break2');
                    break;
                }
            }
            Log::info($data);
            RosterCreate::dispatch($data, $extraData);
            //$this->dispatch(new RosterCreate($data, $extraData));
            Log::info("dispatch Done ");
        }
    }

    public function batchWise($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation, $database_name)
    {
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person??0;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $rostersbeforetime = $this->checkBeforeTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $order_details = Order::find($orders_id);
        $data = [];

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        } elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        } else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'               => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            if(!empty($oneagent->device_token) && $oneagent->is_available == 1){
                $data = [
                    'order_id'            => $orders_id,
                    'driver_id'           => $agent_id,
                    'notification_time'   => $time,
                    'notification_befor_time' => $rostersbeforetime,
                    'type'                => $allcation_type,
                    'client_code'         => Auth::user()->code,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => $randem,
                    'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                ];
                $this->dispatch(new RosterCreate($data, $extraData));
            }
        } else {
            $getgeo = DriverGeo::where('geo_id', $geo)->with(
                        [
                            'agent'=> function ($o) use ($cash_at_hand, $date) {
                                $o->orderBy('id', 'DESC')->with(['logs' => function ($g) {
                                    $g->orderBy('id', 'DESC');
                                }
                                    ,'order'=> function ($f) use ($date) {
                                        $f->whereDate('order_time', $date)->with('task');
                                    }]);
                            }
                        ])->get()->toArray();

            //this function is give me nearest drivers list accourding to the the task location.

            $distenseResult = $this->haversineGreatCircleDistance($getgeo, $finalLocation, $unit, $max_redius, $max_task);

            if(!empty($distenseResult)){
                for ($i = 1; $i <= $try; $i++) {
                    $counter = 0;
                    foreach ($distenseResult as $key =>  $geoitem) {
                        if(!empty($geoitem['device_token'])){
                            $datas = [
                                'order_id'            => $orders_id,
                                'driver_id'           => $geoitem['driver_id'],
                                'notification_time'   => $time,
                                'notification_befor_time' => $rostersbeforetime,
                                'type'                => $allcation_type,
                                'client_code'         => Auth::user()->code,
                                'created_at'          => Carbon::now()->toDateTimeString(),
                                'updated_at'          => Carbon::now()->toDateTimeString(),
                                'device_type'         => $geoitem['device_type'],
                                'device_token'        => $geoitem['device_token'],
                                'detail_id'           => $randem,
                                'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                            ];
                            array_push($data, $datas);
                        }
                        $counter++;
                        if ($counter == $maxsize) {
                            $time = Carbon::parse($time)->addSeconds($expriedate)->format('Y-m-d H:i:s');
                            $rostersbeforetime = Carbon::parse($rostersbeforetime)->addSeconds($expriedate)->format('Y-m-d H:i:s');

                            $counter = 0;
                        }
                        if ($allcation_type == 'N' && 'ACK') {
                            break;
                        }
                    }
                    $time = Carbon::parse($time)->addSeconds($expriedate + 10)->format('Y-m-d H:i:s');

                    $rostersbeforetime = Carbon::parse($rostersbeforetime)->addSeconds($expriedate + 10)->format('Y-m-d H:i:s');


                    if ($allcation_type == 'N' && 'ACK') {
                        break;
                    }
                }
                $this->dispatch(new RosterCreate($data, $extraData)); // job for create roster
            }
        }
    }


    public function roundRobin($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $database_name)
    {
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person??0;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $rostersbeforetime = $this->checkBeforeTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $order_details = Order::find($orders_id);
        $data = [];

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        } elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        } else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'               => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            if(!empty($oneagent->device_token) && $oneagent->is_available == 1){
                $data = [
                    'order_id'            => $orders_id,
                    'driver_id'           => $agent_id,
                    'notification_time'   => $time,
                    'notification_befor_time' => $rostersbeforetime,
                    'type'                => $allcation_type,
                    'client_code'         => Auth::user()->code,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => $randem,
                    'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                ];
                $this->dispatch(new RosterCreate($data, $extraData));
            }
        } else {
            $getgeo = DriverGeo::where('geo_id', $geo)->with(
                        [
                            'agent'=> function ($o) use ($cash_at_hand, $date) {
                                $o->orderBy('id', 'DESC')->with(['logs','order'=> function ($f) use ($date) {
                                    $f->whereDate('order_time', $date)->with('task');
                                }]);
                            }
                        ])->get()->toArray();

            //this function give me the driver list accourding to who have liest task for the current date

            $distenseResult = $this->roundCalculation($getgeo, $finalLocation, $unit, $max_redius, $max_task);

            if(!empty($distenseResult)){
                for ($i = 1; $i <= $try; $i++) {
                    foreach ($distenseResult as $key =>  $geoitem) {
                        if(!empty($geoitem['device_token'])){
                            $datas = [
                                'order_id'            => $orders_id,
                                'driver_id'           => $geoitem['driver_id'],
                                'notification_time'   => $time,
                                'notification_befor_time' => $rostersbeforetime,
                                'type'                => $allcation_type,
                                'client_code'         => Auth::user()->code,
                                'created_at'          => Carbon::now()->toDateTimeString(),
                                'updated_at'          => Carbon::now()->toDateTimeString(),
                                'device_type'         => $geoitem['device_type'],
                                'device_token'        => $geoitem['device_token'],
                                'detail_id'           => $randem,
                                'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                            ];

                            $time = Carbon::parse($time)->addSeconds($expriedate)->format('Y-m-d H:i:s');
                            $rostersbeforetime = Carbon::parse($rostersbeforetime)->addSeconds($expriedate)->format('Y-m-d H:i:s');

                            array_push($data, $datas);
                        }

                        if ($allcation_type == 'N' && 'ACK') {
                            break;
                        }
                    }

                    $time = Carbon::parse($time)->addSeconds($expriedate +10)->format('Y-m-d H:i:s');
                    $rostersbeforetime = Carbon::parse($rostersbeforetime)->addSeconds($expriedate +10)->format('Y-m-d H:i:s');

                    if ($allcation_type == 'N' && 'ACK') {
                        break;
                    }
                }

                $this->dispatch(new RosterCreate($data, $extraData));      // job for insert data in roster table for send notification
            }
        }
    }

    public function roundCalculation($getgeo, $finalLocation, $unit, $max_redius, $max_task)
    {
        $extraarray = [];
        foreach ($getgeo as $item) {
            $count = isset($item['agent']['order']) ? count($item['agent']['order']):0;
            if (($max_task > $count) && !empty($item['agent']['device_token']) && $item['agent']['is_available'] == 1) {
                $data = [
                    'driver_id'    =>  $item['agent']['id'],
                    'device_type'  =>  $item['agent']['device_type'],
                    'device_token' =>  $item['agent']['device_token'],
                    'task_count'   =>  $count,
                ];
                array_push($extraarray, $data);
            }
        }

        $allsort = [];
        if(!empty($extraarray)){
            $allsort =  array_values(Arr::sort($extraarray, function ($value) {
                            return $value['task_count'];
                        }));
        }

        return $allsort;
    }

    public function haversineGreatCircleDistance($getgeo, $finalLocation, $unit, $max_redius, $max_task)
    {
        // convert from degrees to radians
        $earthRadius = 6371;  // earth radius in km
        $latitudeFrom  = $finalLocation->latitude;
        $longitudeFrom = $finalLocation->longitude;
        $lastarray     = [];
        $extraarray    = [];
        foreach ($getgeo as $item) {
            $latitudeTo  = $item['agent']['logs']['lat']??'';
            $longitudeTo = $item['agent']['logs']['long']??'';
            if (!empty($latitudeFrom) && !empty($latitudeFrom) && !empty($latitudeTo) && !empty($longitudeTo) && !empty($latitudeTo) && !empty($longitudeTo) && !empty($item['agent']['device_token']) && $item['agent']['is_available'] == 1) {
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo   = deg2rad($latitudeTo);
                $lonTo   = deg2rad($longitudeTo);
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

                $final = round($angle * $earthRadius);
                $count = isset($item['agent']['order']) ? count($item['agent']['order']):0;
                if ($unit == 'metric') {
                    if ($final <= $max_redius && $max_task > $count) {
                        $data = [
                            'driver_id'    =>  $item['agent']['logs']['agent_id'],
                            'device_type'  =>  $item['agent']['device_type'],
                            'device_token' =>  $item['agent']['device_token'],
                            'distance'     =>  $final
                        ];
                        array_push($extraarray, $data);
                    }
                } else {
                    if ($final <= $max_redius && $max_task > $count) {
                        $data = [
                            'driver_id'    =>  $item['agent']['logs']['agent_id'],
                            'device_type'  =>  $item['agent']['device_type'],
                            'device_token' =>  $item['agent']['device_token'],
                            'distance'     =>  round($final * 0.6214)
                        ];
                        array_push($extraarray, $data);
                    }
                }
            }
        }
        $allsort = [];
        if(!empty($extraarray)){
            $allsort =  array_values(Arr::sort($extraarray, function ($value) {
                            return $value['distance'];
                        }));
        }

        return $allsort;
    }

    public function checkTimeDiffrence($notification_time, $beforetime)
    {
        $to   = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
        $from = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::parse($notification_time)->format('Y-m-d H:i:s'));
        $diff_in_minutes = $to->diffInMinutes($from);
        if ($diff_in_minutes < $beforetime) {
            return  Carbon::now()->toDateTimeString();
        } else {
            return  $notification_time;
        }
    }


    public function createRoster($send_loc_id, $database_name)
    {
        $getletlong = DB::connection($database_name)->table('locations')->where('id', $send_loc_id)->first();
        $lat = $getletlong->latitude;
        $long = $getletlong->longitude;
        return $check = $this->findLocalityByLatLng($lat, $long, $database_name);
    }

    public function findLocalityByLatLng($lat, $lng, $database_name)
    {
        // get the locality_id by the coordinate //
        $latitude_y = $lat;
        $longitude_x = $lng;
        $localities = DB::connection($database_name)->table('geos')->get()->toArray();
        if (empty($localities)) {
            return false;
        }

        foreach ($localities as $k => $locality) {

            if(!empty($locality->polygon)){
                $geoLocalitie = DB::connection($database_name)->table('geos')->where('id', $locality->id)->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $lat . " " . $lng . ")'))")->first();
                if(!empty($geoLocalitie)){
                    return $locality->id;
                }
            }else{
                $all_points = $locality->geo_array;
                $temp = $all_points;
                $temp = str_replace('(', '[', $temp);
                $temp = str_replace(')', ']', $temp);
                $temp = '[' . $temp . ']';
                $temp_array =  json_decode($temp, true);

                foreach ($temp_array as $n => $v) {
                    $data[] = [
                        'lat' => $v[0],
                        'lng' => $v[1]
                    ];
                }

                // $all_points[]= $all_points[0]; // push the first point in end to complete
                $vertices_x = $vertices_y = array();
                foreach ($data as $key => $value) {
                    $vertices_y[] = $value['lat'];
                    $vertices_x[] = $value['lng'];
                }

                $points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
                $points_polygon;
                if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) {
                    return $locality->id;
                }
            }
        }
        return false;
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
            if ((($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]))) {
                $c = !$c;
            }
        }
        return $c;
    }
}
