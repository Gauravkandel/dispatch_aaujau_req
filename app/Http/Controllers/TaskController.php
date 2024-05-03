<?php

namespace App\Http\Controllers;

use Session;
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
use App\Model\Geo;
use App\Model\Order;
use App\Model\csvOrderImport;
use App\Model\Timezone;
use App\Model\AgentLog;
use App\Model\{BatchAllocation, BatchAllocationDetail, Team,TeamTag, SubscriptionInvoicesDriver};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\RosterCreate;
use App\Models\RosterDetail;
use Illuminate\Support\Arr;
use App\Jobs\scheduleNotification;
use Log, DataTables, DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\RoutesExport;
use Excel;
use GuzzleHttp\Client as Gclient;
use App\Http\Controllers\Api\BaseController;
use App\Traits\ApiResponser;
use App\Imports\OrderImport;

class TaskController extends BaseController
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $timezone = $user->timezone ?? 251;
        $tz = new Timezone();
        $client_timezone = $tz->timezone_name($timezone);

        $check = '';
        if ($request->has('status') && $request->status != 'all') {
            $check = $request->status;
        } else {
            $check = 'unassigned';
        }
        $agentids =[];
        $agents = Agent::orderBy('id', 'DESC');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            });
            $agentids = $agents->pluck('id');
        }
        $agents = $agents->where('is_approved', 1)->get();

        $team_tags = TeamTag::whereHas('team', function($q) use($user){
            $q->where('manager_id', $user->id);
        })->pluck('tag_id');

        $all =  Order::where('status', '!=', null);

        if($user->is_superadmin == 0 && $user->all_team_access == 0){
            $all = $all->where(function($q) use($agentids) {
                $q->whereIn('driver_id', $agentids)->orWhereNull('driver_id');
            });

            $all = $all->wherehas('allteamtags', function($query) use($team_tags) {
                $query->whereIn('tag_id', $team_tags);
            });
        }
        $all = $all->get();
        $active   =  count($all->where('status', 'assigned'));
        $pending  =  count($all->where('status', 'unassigned'));
        $history  =  count($all->where('status', 'completed'));
        $failed   =  count($all->where('status', 'failed'));
        $preference  = ClientPreference::where('id', 1)->first(['theme','date_format','time_format', 'create_batch_hours']);

        $teamTag   = TagsForTeam::OrderBy('id','asc');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $teamTag = $teamTag->whereHas('assignTeams.team.permissionToManager', function ($query) use($user){
                $query->where('sub_admin_id', $user->id);
            });
        }
        $teamTag = $teamTag->get();

        $agentTag = TagsForAgent::OrderBy('id','asc');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agentTag = $agentTag->whereHas('assignTags.agent.team.permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            });
        }
        $agentTag = $agentTag->get();

        $pricingRule = PricingRule::select('id', 'name');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $pricingRule = $pricingRule->whereHas('priceRuleTags.team.permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            });
        }

        $pricingRule = $pricingRule->get();

        $allcation   = AllocationRule::where('id', 1)->first();



        $employees      = Customer::orderby('name', 'asc')->where('status','Active')->select('id', 'name')->get();
        $employeesCount = count($employees);
        $agentsCount    = count($agents->where('is_approved', 1));
        $csvRoutes = csvOrderImport::orderBy('id','DESC')->limit(15)->get();
        return view('tasks/task')->with([ 'status' => $request->status, 'agentsCount'=>$agentsCount, 'employeesCount'=>$employeesCount, 'active_count' => $active, 'panding_count' => $pending, 'history_count' => $history, 'status' => $check,'preference' => $preference,'agents'=>$agents,'failed_count'=>$failed,'client_timezone'=>$client_timezone, 'csvRoutes'=>$csvRoutes]);
    }


    public function batchlist(Request $request)
    {
        $user = Auth::user();
        $timezone = $user->timezone ?? 251;
        $tz = new Timezone();
        $client_timezone = $tz->timezone_name($timezone);
        $preference  = ClientPreference::where('id', 1)->first(['theme','date_format','time_format']);

        $agentids =[];
        $agents = Agent::orderBy('id', 'DESC');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            });
            $agentids = $agents->pluck('id');
        }
        $agents = $agents->where('is_approved', 1)->get();
        $all =  BatchAllocation::with('batchDetails')->orderBy('id','desc');
        $all = $all->get();
       

        $all->map(function ($all)use($preference,$client_timezone) {

            if(!empty($all->batch_time)){
                $timeformat      = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                $orderT           = Carbon::createFromFormat('Y-m-d H:i:s', $all->batch_time, 'UTC');
                
                $orderT->setTimezone($client_timezone);
                $preference->date_format = $preference->date_format ?? 'm/d/Y';
            }

            $all['batchTime'] = date(''.$preference->date_format.' '.$timeformat.'', strtotime($orderT));
            return $all;
        });


        $allcation   = AllocationRule::where('id', 1)->first();

        $employees      = Customer::orderby('name', 'asc')->where('status','Active')->select('id', 'name')->get();
        $employeesCount = count($employees);
        // $agentsCount    = count($agents->where('is_approved', 1));
        // 'active_count' => $active, 'panding_count' => $pending, 'history_count' => $history, 'status' => $check,
        return view('tasks/batch')->with([ 'status' => $request->status, 'agents'=>$agents,'client_timezone'=>$client_timezone,'batchs'=>$all]);
    }

    public function batchDetails(Request $request)
    {
        $user = Auth::user();
        $preference = ClientPreference::where('id', 1)->first(['theme','date_format','time_format']);
        $timezone = $user->timezone ?? 251;

        $all =  BatchAllocation::with('batchDetails')->where('id',$request->id)->first();
            $table = '<table class="table table-striped dt-responsive nowrap w-100 agents-datatable"><tr><th>Sr.No </th><th>Order No</th><th>Phone No</th><th>Due Time</th><th>Customer</th><th>Tracking Url</th></tr>';
            foreach($all->batchDetails as $no=>  $order){
                $trackUrl = url('/order/tracking/'.$user->code.'/'.$order->order->unique_id.'');

                $tz              = new Timezone();
                $client_timezone = $tz->timezone_name($timezone);
                if(!empty($order->order->order_time)){
                    $timeformat      = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                    $orderT           = Carbon::createFromFormat('Y-m-d H:i:s', $order->order->order_time, 'UTC');
                    
                    $orderT->setTimezone($client_timezone);
                    $preference->date_format = $preference->date_format ?? 'm/d/Y';
                }


                $table .= '<tr>
                    <td>'.++$no.'</td>
                    <td>'.$order->order->order_number.'</td>
                    <td>'.$order->order->customer->phone_number.'</td>
                    <td>'.date(''.$preference->date_format.' '.$timeformat.'', strtotime($orderT)).'</td>
                    <td>'.$order->order->customer->name.'</td>
                    <td><a href="'.$trackUrl.'" target="_blank" >View</a></td>
                </tr>';
            }

            $table .='</table>';
      return json_encode(['success'=>$table]);
    }

    public function taskFilter(Request $request)
    {
        $user = Auth::user();
        $timezone = $user->timezone ?? 251;

        $team_tags = TeamTag::whereHas('team', function($q) use($user){
            $q->where('manager_id', $user->id);
        })->pluck('tag_id');

        $orders = Order::with(['customer', 'location', 'taskFirst', 'agent', 'task.location']);

        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agents = Agent::orderBy('id', 'DESC');
            $agentids = $agents->whereHas('team.permissionToManager', function ($query) use($user) {
                $query->where('sub_admin_id', $user->id);
            })->pluck('id');

            $orders = $orders->where(function($q) use($agentids) {
                $q->whereIn('driver_id', $agentids)->orWhereNull('driver_id');
            });

            $orders = $orders->wherehas('allteamtags', function($query) use($team_tags) {
                $query->whereIn('tag_id', $team_tags);
            });
        }

        $orders = $orders->where('status', $request->routesListingType)->where('status', '!=', null)->orderBy('updated_at', 'desc');

        $preference = ClientPreference::where('id', 1)->first(['theme','date_format','time_format']);

        return Datatables::of($orders)
                ->addColumn('customer_id', function ($orders) use ($request) {
                    $customerID = !empty($orders->customer->id)? $orders->customer->id : '';
                    $length = strlen($customerID);
                    if($length < 4){
                        $customerID = str_pad($customerID, 4, '0', STR_PAD_LEFT);
                    }
                    return $customerID;
                })
                ->addColumn('customer_name', function ($orders) use ($request) {
                    $customerName = !empty($orders->customer->name)? $orders->customer->name : '';
                    return $customerName;
                })
                ->addColumn('phone_number', function ($orders) use ($request) {
                    $phoneNumber = !empty($orders->customer->phone_number)? $orders->customer->phone_number : '';
                    return $phoneNumber;
                })
                ->addColumn('agent_name', function ($orders) use ($request) {
                    $checkActive = (!empty($orders->agent->name) && $orders->agent->is_available == 1) ? ' '.__('Active') : ' '. __('InActive');
                    $agentName   = !empty($orders->agent->name)? $orders->agent->name.$checkActive : '';
                    return $agentName;
                })
                ->addColumn('order_number', function ($orders) use ($request) {
                    return '<a href="'.route('tasks.edit', $orders->id).'" title="Edit Route">'.$orders->order_number.'</a>';
                })
                ->addColumn('order_time', function ($orders) use ($request, $timezone, $preference) {
                    $tz              = new Timezone();
                    $client_timezone = $tz->timezone_name($timezone);
                    if(!empty($orders->order_time)):
                        $timeformat      = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                        $order           = Carbon::createFromFormat('Y-m-d H:i:s', $orders->order_time, 'UTC');
                        
                        $order->setTimezone($client_timezone);
                        $preference->date_format = $preference->date_format ?? 'm/d/Y';
                        $convertabledate = date(''.$preference->date_format.' '.$timeformat.'', strtotime($order));
                        return $convertabledate.'<br/>'.$order->diffForHumans();
                    else:
                        return '';
                    endif;
                })
                ->addColumn('short_name', function ($orders) use ($request) {
                    $routes = array();
                    foreach($orders->task as $task){
                        if($task->task_type_id == 1){
                            $taskType    = "Pickup";
                            $pickupClass = "yellow_";
                        }else if($task->task_type_id == 2){
                            $taskType    = "Dropoff";
                            $pickupClass = "green_";
                        }else{
                            $taskType    = "Appointment";
                            $pickupClass = "assign_";
                        }

                        $shortName  = (!empty($task->location->short_name)? $task->location->short_name:'');
                        $address    = (!empty($task->location->address)? $task->location->address:'');

                        $addressArr   = explode(' ',trim($address));
                        $finalAddress = (!empty($addressArr[0])) ? $addressArr[0] : '';
                        $finalAddress = (!empty($addressArr[1])) ? $addressArr[0].' '.$addressArr[1] : $finalAddress.'';
                        $finalAddress = (!empty($addressArr[2])) ? $addressArr[0].' '.$addressArr[1].' '.$addressArr[2] : $finalAddress.'';
                        $finalAddress = (!empty($addressArr[3])) ? $addressArr[0].' '.$addressArr[1].' '.$addressArr[2].' '.$addressArr[3] : $finalAddress.'';
                        $finalAddress = (!empty($addressArr[4])) ? $addressArr[0].' '.$addressArr[1].' '.$addressArr[2].' '.$addressArr[3].' '.$addressArr[4] : $finalAddress.'';
                        $finalAddress = (!empty($addressArr[5])) ? $addressArr[0].' '.$addressArr[1].' '.$addressArr[2].' '.$addressArr[3].' '.$addressArr[4].' '.$addressArr[5] : $finalAddress.'';
                        $finalAddress = (!empty($addressArr[6])) ? $addressArr[0].' '.$addressArr[1].' '.$addressArr[2].' '.$addressArr[3].' '.$addressArr[4].' '.$addressArr[5].'' : $finalAddress;
                        $routes[]     = array('taskType'=>__($taskType), 'pickupClass'=>$pickupClass, 'shortName'=>$shortName, 'toolTipAddress'=>$address, 'address'=> $finalAddress);
                    }
                    return json_encode($routes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                })
                
                ->editColumn('updated_at', function ($orders) use ($request, $timezone, $preference) {
                    $tz              = new Timezone();
                    $client_timezone = $tz->timezone_name($timezone);
                    $timeformat      = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                    $order           = Carbon::createFromFormat('Y-m-d H:i:s', $orders->updated_at, 'UTC');
                    $order->setTimezone($client_timezone);
                    $preference->date_format = $preference->date_format ?? 'm/d/Y';
                    return date(''.$preference->date_format.' '.$timeformat.'', strtotime($order));
                })
                ->addColumn('action', function ($orders) use ($request) {
                    $action = '<div class="form-ul" style="width: 60px;">
                                    <div class="inner-div">
                                        <div class="set-size">
                                            <a href1="#" href="'.route('tasks.edit', $orders->id).'" class="action-icon editIconBtn mr-2" title="Edit Route">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </div>
                                    </div>';
                        if($orders->status!='completed'):
                         $action.='<div class="inner-div">
                                        <form class="mb-0" id="taskdelete'.$orders->id.'" method="POST" action="'.route('tasks.destroy', $orders->id).'">
                                            <input type="hidden" name="_token" value="'.csrf_token().'" />
                                            <input type="hidden" name="_method" value="DELETE">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete" taskid="'.$orders->id.'"></i></button>
                                            </div>
                                        </form>
                                    </div>';
                        endif;
                        $action.='</div>';
                    return $action;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                       
                        $search = $request->get('search');
                        $instance->where(function($query) use($search){
                            $query->where('order_number', 'Like', '%'.$search.'%')
                            ->orWhereHas('customer', function($q) use($search){
                                $q->where('name', 'Like', '%'.$search.'%')
                                ->orWhere('phone_number', 'Like', '%'.$search.'%');
                            })
                            ->orWhereHas('agent', function($q) use($search){
                                $q->where('name', 'Like', '%'.$search.'%');
                            });
                        });
                    }
                }, true)
                ->rawColumns(['action', 'order_number', 'order_time'])
                ->make(true);
    }

    public function tasksExport(Request $request){
        $header = [
                [
                    'Sr. No.',
                    'Customer',
                    'Phone.No',
                    getAgentNomenclature(),
                    'Due Time',
                    'Routes',
                    'Status',
                    'Cash To Be Collected',
                    'Base Price',
                    'Base Duration',
                    'Base Distance',
                    'Base Waiting',
                    'Duration Price',
                    'Waiting Price',
                    'Distance Fee',
                    'Cancel Fee',
                    'Agent Commission Percentage',
                    'Agent Commission Fixed',
                    'Freelancer Commission Percentage',
                    'Freelancer Commission Fixed',
                    getAgentNomenclature().' Cost',
                    'Pricing'
                ]
            ];
        $data = array();
        $orders = Order::orderBy('created_at', 'DESC')->with(['customer', 'location', 'taskFirst', 'agent', 'task.location']);
        $orders = $orders->where('status', '!=', null)->get();
        if(!empty($orders)){
            $tz              = new Timezone();
            $client_timezone = $tz->timezone_name(Auth::user()->timezone);
            $preference      = ClientPreference::where('id', 1)->first(['theme','date_format','time_format']);
            $timeformat      = $preference->time_format == '24' ? 'H:i:s':'g:i a';

            $i = 1;
            foreach ($orders as $key => $value) {
                $ndata = [];
                $ndata[] = $i;
                $ndata[] = (isset($value->customer->name))?$value->customer->name:'';
                $ndata[] = (isset($value->customer->phone_number))?$value->customer->phone_number:'';
                $ndata[] = empty($value->agent) ? 'Unassigned' : $value->agent->name;

                $order = Carbon::createFromFormat('Y-m-d H:i:s', $value->order_time, 'UTC');
                $order->setTimezone($client_timezone);
                $preference->date_format = $preference->date_format ?? 'm/d/Y';
                $ndata[] = date(''.$preference->date_format.' '.$timeformat.'', strtotime($order));

                $task='';
                    foreach ($value->task as $singletask) {
                        if($singletask->task_type_id==1)
                        {
                            $tasktype = "Pickup";
                            $pickup_class = "yellow_";
                        }elseif($singletask->task_type_id==2)
                        {
                            $tasktype = "Dropoff";
                            $pickup_class = "green_";
                        }else{
                            $tasktype = "Appointment";
                            $pickup_class = "assign_";
                        }
                        $shortName = (isset($singletask->location->short_name))?$singletask->location->short_name.', ':'';
                        $address   = (isset($singletask->location->address))?$singletask->location->address:'';
                        if($task){
                            $task.=" & ";
                        }
                        $task.=$tasktype.', '.$shortName.$address;
                    }

                $ndata[] = $task;
            //     $ndata[] = '0';
                $ndata[] = $value->status;
                $ndata[] = $value->cash_to_be_collected;
                $ndata[] = $value->base_price;
                $ndata[] = $value->base_duration;
                $ndata[] = $value->base_distance;
                $ndata[] = $value->base_waiting;
                $ndata[] = $value->duration_price;
                $ndata[] = $value->waiting_price;
                $ndata[] = $value->distance_fee;
                $ndata[] = $value->cancel_fee;
                $ndata[] = $value->agent_commission_percentage;
                $ndata[] = $value->agent_commission_fixed;
                $ndata[] = $value->freelancer_commission_percentage;
                $ndata[] = $value->freelancer_commission_fixed;
                $ndata[] = $value->driver_cost;
                $ndata[] = $value->order_cost;
                $data[]  = $ndata;
                $i++;
            }
        }


        return Excel::download(new RoutesExport($data, $header), "task.xlsx");
    }

    // function for saving new order
    public function newtasks(Request $request)
    {
        try {
            DB::beginTransaction();

            $loc_id = $cus_id = $send_loc_id = $newlat = $newlong = 0;
            $iinputs = $request->toArray();
            
            $old_address_ids = array();
            foreach ($iinputs as $key => $value) {
                if (substr_count($key, "old_address_id") == 1) {
                    $old_address_ids[] = $key;
                }
            }

            $images = [];
            $last = '';
            $customer = [];
            $finalLocation = [];
            $taskcount = 0;
            $latitude  = [];
            $longitude = [];
            $percentage = 0;

            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $unique_order_id = substr(str_shuffle(str_repeat($pool, 5)), 0, 6);
            $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

            //setting timezone from id
            $tz = new Timezone();
            $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

            //save task images on s3 bucket
            if (isset($request->file) && count($request->file) > 0) {
                $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
                $folder = 'client_' . $folder;
                $files = $request->file('file');
                foreach ($files as $key => $value) {
                    $file = $value;
                    $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
                    $s3filePath = '/assets/' . $folder . '/' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                    array_push($images, $path);
                }
                $last = implode(",", $images);
            }

            //create new customer for task or get id of old customer
            if (!isset($request->ids)) {
                $customer = Customer::where('email', '=', $request->email)->first();
                if (isset($customer->id)) {
                    $cus_id = $customer->id;
                } else {
                    $cus = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'dial_code' => $request->dialCode,
                    ];
                    $customer = Customer::create($cus);
                    $cus_id = $customer->id;
                }
            } else {
                $cus = [
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'dial_code' => $request->dialCode,
                ];
                $customerupdate = Customer::where('id', $request->ids)->update($cus);
                $cus_id = $request->ids;
                $customer = Customer::where('id', $request->ids)->first();
            }

            $settime = ($request->task_type=="schedule") ? $request->schedule_time : Carbon::now()->toDateTimeString();
            $notification_time = ($request->task_type=="schedule")? Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC') : Carbon::now()->toDateTimeString();


            //here order save code is started

            $agent_id          = $request->allocation_type === 'm' ? $request->agent : null;

            $order = [
                'order_number'                    => generateOrderNo(),
                'customer_id'                     => $cus_id,
                'recipient_phone'                 => $request->recipient_phone,
                'Recipient_email'                 => $request->recipient_email,
                'task_description'                => $request->task_description,
                'driver_id'                       => $agent_id,
                'auto_alloction'                  => $request->allocation_type,
                'images_array'                    => $last,
                'order_type'                      => $request->task_type,
                'order_time'                      => $notification_time,
                'status'                          => $agent_id != null ? 'assigned' : 'unassigned',
                'cash_to_be_collected'            => $request->cash_to_be_collected,
                'unique_id'                       => $unique_order_id,
                'call_back_url'                   => $request->call_back_url??null
            ];
        
            $orders = Order::create($order);
        
            //here is task save code is started

            $dep_id = null; // this is used as dependent task id
            $pickup_quantity = 0;
            $drop_quantity   = 0;
            foreach ($request->task_type_id as $key => $value) {
                $taskcount++;
                if (isset($request->address[$key])) {
                    $loc = [
                        'short_name'     => $request->short_name[$key],
                        'post_code'      => $request->post_code[$key],
                        'flat_no'        => !empty($request->flat_no[$key])? $request->flat_no[$key] : '',
                        'email'          => $request->address_email[$key],
                        'phone_number'   => $request->address_phone_number[$key],
                    ];



                    $Loction = Location::updateOrCreate(
                        ['latitude' => $request->latitude[$key], 'longitude' => $request->longitude[$key],'address' => $request->address[$key],'customer_id' => $cus_id],
                        $loc
                    );

                    $loc_id = $Loction->id;
                    $send_loc_id = $loc_id;
                } else {
                    if ($key == 0) {
                        $loc_id = $request->old_address_id;
                        $send_loc_id = $loc_id;
                    } else {
                        $loc_id = $request->input($old_address_ids[$key]);
                        $send_loc_id = $loc_id;
                    }
                }

                $location = Location::where('id', $loc_id)->first();
                if ($location->customer_id != $cus_id) {
                    $newloc = [
                    'short_name'   => $location->short_name,
                        'post_code'    =>$location->post_code,
                        'flat_no'      => $location->flat_no,
                        'email'        => $location->address_email,
                        'phone_number' => $location->address_phone_number,
                    ];

                    $Loction = Location::updateOrCreate(
                        ['latitude' => $location->latitude, 'longitude' => $location->longitude, 'address' => $location->address,'customer_id'  => $cus_id],
                        $newloc
                    );
                }

                $loc_id = $location->id;
                if ($key == 0) {
                    $finalLocation = $location;
                }

                array_push($latitude, $location->latitude);
                array_push($longitude, $location->longitude);

                $task_appointment_duration = empty($request->appointment_date[$key]) ? '0' : $request->appointment_date[$key];
                $data = [
                    'order_id'                   => $orders->id,
                    'task_type_id'               => $value,
                    'location_id'                => $loc_id,
                    'appointment_duration'       => $task_appointment_duration,
                    'dependent_task_id'          => $dep_id,
                    'task_status'                => $agent_id != null ? 1 : 0,
                    'created_at'                 => $notification_time,
                    'assigned_time'              => $notification_time,
                    'barcode'                    => $request->barcode[$key],
                    'quantity'                   => $request->quantity[$key],
                    'alcoholic_item'             => !empty($request->alcoholic_item[$key])? $request->alcoholic_item[$key] : '',
                ];
                $task = Task::create($data);
                $dep_id = $task->id;

                //for net quantity
                if ($value == 1) {
                    $pickup_quantity = $pickup_quantity+$request->quantity[$key];
                } elseif ($value == 2) {
                    $drop_quantity   = $drop_quantity+$request->quantity[$key];
                }
                $net_quantity = $pickup_quantity - $drop_quantity;
            }

            //get pricing rule  for save with every order

            $geo = null;
            if ($request->allocation_type === 'a') {

                $geo = $this->createRoster($send_loc_id);
                $agent_id = null;
            }

            $dayname = Carbon::parse($notification_time)->format('l');
            $time    = Carbon::parse($notification_time)->format('H:i:s');

            if((isset($request->agent_tag) && !empty($request->agent_tag)) && (isset($request->team_tag) && !empty($request->team_tag)) && ($geo!='' && $geo != null)):
                $pricingRule = PricingRule::orderBy('id', 'desc')->whereHas('priceRuleTags.tagsForAgent', function($q)use($request){
                    $q->whereIn('id',$request->agent_tag);
                })->whereHas('priceRuleTags.tagsForTeam', function($q)use($request){
                    $q->whereIn('id',$request->team_tag);
                })->whereHas('priceRuleTags.geoFence', function($q)use($geo){
                    $q->where('id',$geo);
                })
                ->where(function($q) use ($dayname, $time){
                    $q->where('apply_timetable', '!=', 1)
                    ->orWhereHas('priceRuleTimeframe', function($query) use ($dayname, $time){
                        $query->where('is_applicable', 1)
                            ->Where('day_name', '=', $dayname)
                            ->whereTime('start_time', '<=', $time)
                            ->whereTime('end_time', '>=', $time);
                    });
                })->first();
            endif;
            
            if(empty($pricingRule))
            $pricingRule = PricingRule::where('id', 1)->first();

            //accounting for task duration distanse
            $getdata = $this->GoogleDistanceMatrix($latitude, $longitude);
            $paid_duration = $getdata['duration'] - $pricingRule->base_duration;
            $paid_distance = $getdata['distance'] - $pricingRule->base_distance;
            $paid_duration = $paid_duration < 0 ? 0 : $paid_duration;
            $paid_distance = $paid_distance < 0 ? 0 : $paid_distance;
            $total         = $pricingRule->base_price + ($paid_distance * $pricingRule->distance_fee) + ($paid_duration * $pricingRule->duration_price);

            $agent_commission_fixed = $pricingRule->agent_commission_fixed;
            $agent_commission_percentage = $pricingRule->agent_commission_percentage;
            $freelancer_commission_fixed = $pricingRule->freelancer_commission_fixed;
            $freelancer_commission_percentage = $pricingRule->freelancer_commission_percentage;
            if (isset($agent_id)) {
                $agent_details = Agent::where('id', $agent_id)->first();
                if ($agent_details->type == 'Employee') {
                    $percentage = $agent_commission_fixed + (($total / 100) * $agent_commission_percentage);
                } else {
                    $percentage = $freelancer_commission_fixed + (($total / 100) * $freelancer_commission_percentage);
                }

                $now = Carbon::now()->toDateString();
                $driver_subscription = SubscriptionInvoicesDriver::where('driver_id', $agent_id)->where('end_date', '>', $now)->orderBy('end_date', 'desc')->first();
                if($driver_subscription && ($driver_subscription->driver_type == $agent_details->type)){
                    if ($driver_subscription->driver_type == 'Employee') {
                        $agent_commission_fixed = $driver_subscription->driver_commission_fixed;
                        $agent_commission_percentage = $driver_subscription->driver_commission_percentage;
                        $freelancer_commission_fixed = null;
                        $freelancer_commission_percentage = null;
                    } else {
                        $agent_commission_fixed = null;
                        $agent_commission_percentage = null;
                        $freelancer_commission_fixed = $driver_subscription->driver_commission_fixed;
                        $freelancer_commission_percentage = $driver_subscription->driver_commission_percentage;
                    }
                    $percentage = $driver_subscription->driver_commission_fixed + (($total / 100) * $driver_subscription->driver_commission_percentage);
                }
            }
            //update order with order cost details
            $updateorder = [
                'actual_time'                     => $getdata['duration'],
                'actual_distance'                 => $getdata['distance'],
                'base_price'                      => $pricingRule->base_price,
                'base_duration'                   => $pricingRule->base_duration,
                'base_distance'                   => $pricingRule->base_distance,
                'base_waiting'                    => $pricingRule->base_waiting,
                'duration_price'                  => $pricingRule->duration_price,
                'waiting_price'                   => $pricingRule->waiting_price,
                'distance_fee'                    => $pricingRule->distance_fee,
                'cancel_fee'                      => $pricingRule->cancel_fee,
                'agent_commission_percentage'     => $agent_commission_percentage,
                'agent_commission_fixed'          => $agent_commission_fixed,
                'freelancer_commission_percentage'=> $freelancer_commission_percentage,
                'freelancer_commission_fixed'     => $freelancer_commission_fixed,
                'order_cost'                      => $total,
                'driver_cost'                     => $percentage,
                'net_quantity'                    => $net_quantity

            ];

            Order::where('id', $orders->id)->update($updateorder);

            //task tages save code is here
            if (isset($request->allocation_type) && $request->allocation_type === 'a') {
                if (isset($request->team_tag)) {
                    $orders->teamtags()->sync($request->team_tag);
                }
                if (isset($request->agent_tag)) {
                    $orders->drivertags()->sync($request->agent_tag);
                }
            }

            //this function is called when allocation type is Accept/Reject it find the current task location belongs to which geo fence

            // task schdule code is hare

            $allocation = AllocationRule::where('id', 1)->first();
            if ($request->task_type != 'now') {

                $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

                //setting timezone from id
                $tz = new Timezone();
                $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

                $beforetime = (int)$auth->getAllocation->start_before_task_time;
                $to = new \DateTime("now", new \DateTimeZone('UTC'));
                $sendTime = Carbon::now();
                $to = Carbon::parse($to)->format('Y-m-d H:i:s');
                $from = Carbon::parse($notification_time)->format('Y-m-d H:i:s');
                $datecheck = 0;
                $to_time = strtotime($to);
                $from_time = strtotime($from);
                
                if ($to_time >= $from_time) {
                    return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
                }

                $diff_in_minutes = round(abs($to_time - $from_time) / 60);
                $schduledata = [];
                if ($diff_in_minutes > $beforetime) {
                    $notification_befor_time =   Carbon::parse($notification_time)->subMinutes($beforetime);
                    $finaldelay = (int)$diff_in_minutes - $beforetime;
                    $time = Carbon::parse($sendTime)
                    ->addMinutes($finaldelay)
                    ->format('Y-m-d H:i:s');

                    $schduledata['geo']               = $geo;
                    $schduledata['notification_time'] = $notification_time;
                    $schduledata['agent_id']          = $agent_id;
                    $schduledata['orders_id']         = $orders->id;
                    $schduledata['customer']          = $customer;
                    $schduledata['finalLocation']     = $finalLocation;
                    $schduledata['taskcount']         = $taskcount;
                    $schduledata['allocation']        = $allocation;
                    $schduledata['database']          = $auth;
                    scheduleNotification::dispatch($schduledata)->delay(now()->addMinutes($finaldelay));
                    DB::commit();
                    $orderdata = Order::select('id', 'order_time', 'status', 'driver_id')->with('agent')->where('id', $orders->id)->first();
                    //event(new \App\Events\loadDashboardData($orderdata));
                    return response()->json(['status' => "Success", 'message' => 'Route created Successfully']);
                }
            }

            //this is roster create accounding to the allocation methed
            if ($request->allocation_type === 'a' || $request->allocation_type === 'm') {
                switch ($allocation->auto_assign_logic) {
                    case 'one_by_one':
                        //this is called when allocation type is one by one
                        $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                        break;
                    case 'send_to_all':
                        //this is called when allocation type is send to all
                    Log::info('send_to_all taskController');
                        $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                        break;
                    case 'round_robin':
                        //this is called when allocation type is round robin
                        $this->roundRobin($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                        break;
                    default:
                        //this is called when allocation type is batch wise
                        $this->batchWise($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                }
            }
            DB::commit();
            $orderdata = Order::select('id', 'order_time', 'status', 'driver_id')->with('agent')->where('id', $orders->id)->first();
            //event(new \App\Events\loadDashboardData($orderdata));
            return response()->json(['status' => "Success", 'message' => 'Route created Successfully']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => "failure", 'message' => $e->getMessage()]);
        }
    }

    //function for assigning driver to unassigned orders
    public function assignAgent(Request $request)
    {
        try{
            if($request->type != 'B'){
                $agent_id = $request->has('agent_id') ? $request->agent_id : null;
                $agent_details = Agent::where('id', $agent_id)->where('is_approved', 1)->first();
                if(!empty($agent_details)){
                    $orders = Order::find($request->orders_id);
                    foreach($orders as $order){
                        $percentage = 0.00;
                        $agent_commission_fixed = $order->agent_commission_fixed;
                        $agent_commission_percentage = $order->agent_commission_percentage;
                        $freelancer_commission_fixed = $order->freelancer_commission_fixed;
                        $freelancer_commission_percentage = $order->freelancer_commission_percentage;

                        if ($agent_details->type == 'Employee'){
                            $percentage = $agent_commission_fixed + (($order->order_cost / 100) * $agent_commission_percentage);
                        }else{
                            $percentage = $freelancer_commission_fixed + (($order->order_cost / 100) * $freelancer_commission_percentage);
                        }
                        
                        $now = Carbon::now()->toDateString();
                        $driver_subscription = SubscriptionInvoicesDriver::where('driver_id', $agent_id)->where('end_date', '>', $now)->orderBy('end_date', 'desc')->first();
                        if($driver_subscription && ($driver_subscription->driver_type == $agent_details->type)){
                            if ($driver_subscription->driver_type == 'Employee') {
                                $agent_commission_fixed = $driver_subscription->driver_commission_fixed;
                                $agent_commission_percentage = $driver_subscription->driver_commission_percentage;
                                $freelancer_commission_fixed = null;
                                $freelancer_commission_percentage = null;
                            } else {
                                $agent_commission_fixed = null;
                                $agent_commission_percentage = null;
                                $freelancer_commission_fixed = $driver_subscription->driver_commission_fixed;
                                $freelancer_commission_percentage = $driver_subscription->driver_commission_percentage;
                            }
                            $percentage = $driver_subscription->driver_commission_fixed + (($order->order_cost / 100) * $driver_subscription->driver_commission_percentage);
                        }

                        $order_update = Order::where('id', $order->id)->update([
                            'driver_id' => $agent_id, 
                            'driver_cost' => $percentage, 
                            'status' => 'assigned', 
                            'auto_alloction' => 'm',
                            'agent_commission_fixed' => $agent_commission_fixed,
                            'agent_commission_percentage' => $agent_commission_percentage,
                            'freelancer_commission_fixed' => $freelancer_commission_fixed,
                            'freelancer_commission_percentage' => $freelancer_commission_percentage
                        ]);

                        $task = Task::where('order_id', $order->id)->update(['task_status'=>1]);

                        $orderdata = Order::select('id', 'order_time', 'status', 'driver_id')->with('agent')->where('id', $order->id)->first();
                        //event(new \App\Events\loadDashboardData($orderdata));
                        $this->MassAndEditNotification($order->id, $agent_id);
                    }
                    Session::put('success', __(getAgentNomenclature().' assigned successfully'));
                    return $this->success(__(getAgentNomenclature().' assigned successfully'), 400);
                }else{
                    Session::put('error', __('Invalid '.getAgentNomenclature().' data'));
                    return $this->error(__('Invalid '.getAgentNomenclature().' data'), 400);
                }
            }else{

            $batchs = BatchAllocation::with('batchDetails')->where('id',$request->batchId)->first();
            //dd($batchs->batchDetails);
            
            foreach($batchs->batchDetails as  $k=> $batch)
                {
                    if(!empty($request->agent_id)){
                        $task_id = Order::find($batch->order_id);
                        $agent_details = Agent::where('id', $request->agent_id)->first();

                        $agent_commission_fixed = $task_id->agent_commission_fixed;
                        $agent_commission_percentage = $task_id->agent_commission_percentage;
                        $freelancer_commission_fixed = $task_id->freelancer_commission_fixed;
                        $freelancer_commission_percentage = $task_id->freelancer_commission_percentage;

                        if ($agent_details->type == 'Employee'){
                            $percentage = $task_id->agent_commission_fixed + (($task_id->order_cost / 100) * $task_id->agent_commission_percentage);
                        }else{
                            $percentage = $task_id->freelancer_commission_fixed + (($task_id->order_cost / 100) * $task_id->freelancer_commission_percentage);
                        }

                        $now = Carbon::now()->toDateString();
                        $driver_subscription = SubscriptionInvoicesDriver::where('driver_id', $request->agent_id)->where('end_date', '>', $now)->orderBy('end_date', 'desc')->first();
                        if($driver_subscription && ($driver_subscription->driver_type == $agent_details->type)){
                            if ($driver_subscription->driver_type == 'Employee') {
                                $agent_commission_fixed = $driver_subscription->driver_commission_fixed;
                                $agent_commission_percentage = $driver_subscription->driver_commission_percentage;
                                $freelancer_commission_fixed = null;
                                $freelancer_commission_percentage = null;
                            } else {
                                $agent_commission_fixed = null;
                                $agent_commission_percentage = null;
                                $freelancer_commission_fixed = $driver_subscription->driver_commission_fixed;
                                $freelancer_commission_percentage = $driver_subscription->driver_commission_percentage;
                            }
                            $percentage = $driver_subscription->driver_commission_fixed + (($task_id->order_cost / 100) * $driver_subscription->driver_commission_percentage);
                        }
                    }
                    else{
                        $percentage = 0.00;
                    }

                    $order_update = Order::where('id', $batch->order_id)->update([
                        'driver_id'=>$request->agent_id,
                        'driver_cost'=>$percentage,
                        'status'=>'assigned',
                        'auto_alloction'=>'m',
                        'agent_commission_fixed' => $agent_commission_fixed,
                        'agent_commission_percentage' => $agent_commission_percentage,
                        'freelancer_commission_fixed' => $freelancer_commission_fixed,
                        'freelancer_commission_percentage' => $freelancer_commission_percentage
                    ]);
                    $task         = Task::where('order_id', $batch->order_id)->update(['task_status'=>1]);

                    $batch    = BatchAllocationDetail::where('order_id', $batch->order_id)->update(['agent_id'=>$request->agent_id]);
                
                $batchs->agent_id =$request->agent_id;
                $batchs->save();
                }
                $this->MassAndEditNotification($batchs->batchDetails[0]->order->id, $request->agent_id,$request->batchId);
                return redirect()->back();
            }
        }catch(\Exception $e)
        {
            dd($e->getMessage());
        }
    }

    //function for updating date of orders
    public function assignDate(Request $request)
    {
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        //setting timezone from id
        $tz = new Timezone();
        $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

        $notification_time = Carbon::parse($request->newdate . $auth->timezone ?? 'UTC')->tz('UTC');

        $order_update = Order::whereIn('id', $request->orders_id)->update(['order_time'=>$notification_time]);
    }

    //function for sending bulk notification
    public function MassAndEditNotification($orders_id, $agent_id,$batch="")
    {
        $batchTime = '';
        $batch_id = '';
        if($batch)
        {
            $batch = BatchAllocation::findOrFail($batch);
            $batchTime = $batch->batch_time;
            $batch_id = $batch->batch_no;
        }
       // Log::info('mass and edit notification');
        $order_details = Order::where('id', $orders_id)->with(['customer','agent', 'task.location'])->first();
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $notification_time = $batchTime??$order_details->order_time;
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getPreference->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime); //this function is check the time diffrence and give the notification time
        $rostersbeforetime  = $this->checkBeforeTimeDiffrence($notification_time, $beforetime);
        $randem     = rand(11111111, 99999999);


        $allcation_type = 'ACK';

        foreach ($order_details->task as $key => $value) {
            $taskcount = count($order_details->task);
            $extraData = [
                'customer_name'            => $order_details->customer->name,
                'customer_phone_number'    => $order_details->customer->phone_number,
                'short_name'               => $value->location->short_name,
                'address'                  => $value->location->address,
                'lat'                      => $value->location->latitude,
                'long'                     => $value->location->longitude,
                'task_count'               => $taskcount,
                'unique_id'                => $randem,
                'created_at'               => Carbon::now()->toDateTimeString(),
                'updated_at'               => Carbon::now()->toDateTimeString(),
            ];
            break;
        }

        $oneagent = Agent::where('id', $agent_id)->first();
        $data = [
            'order_id'            => $orders_id,
            'batch_no'            => $batch_id??'',
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
        ];
        // Send message to customer friend 
        try{
            if(isset($order_details->type) && $order_details->type == 1 && strlen($order_details->friend_phone_number) > 8)
            {
                $friend_sms_body = 'Hi '.($order_details->friend_name).', '.($order_details->customer->name??'Our customer').' have booked a ride for you. '.getAgentNomenclature().' '.($oneagent->name??'').' in our '.($oneagent->make_model ?? '').' with license plate '.($oneagent->plate_number??'').' has been assgined.';
                $send = $this->sendSms2($order_details->friend_phone_number , $friend_sms_body);
            }
        }catch(\Exception $e){
            Log::info("Error While sending sms to friend");
        }

        $this->dispatch(new RosterCreate($data, $extraData)); //this job is for create roster in main database for send the notification  in manual alloction
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamTag   = TagsForTeam::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teamTag = $teamTag->whereHas('assignTeams.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $teamTag = $teamTag->get();


        $agentTag = TagsForAgent::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agentTag = $agentTag->whereHas('assignTags.agent.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $agentTag = $agentTag->get();


        $pricingRule = PricingRule::select('id', 'name');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $pricingRule = $pricingRule->whereHas('priceRuleTags.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }

        $pricingRule = $pricingRule->get();


        $allcation   = AllocationRule::where('id', 1)->first();

       $agents = Agent::orderBy('name', 'asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $agents = $agents->where('is_approved', 1)->get();

        $preference  = ClientPreference::where('id', 1)->first(['route_flat_input','route_alcoholic_input']);

        $task_proofs = TaskProof::all();
        $returnHTML = view('modals/add-task-modal')->with(['teamTag' => $teamTag, 'preference'=>$preference, 'agentTag' => $agentTag, 'agents' => $agents, 'pricingRule' => $pricingRule, 'allcation' => $allcation ,'task_proofs' => $task_proofs ])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loc_id = $cus_id = $send_loc_id = $newlat = $newlong = 0;
        $images = [];
        $last = '';
        $customer = [];
        $finalLocation = [];
        $taskcount = 0;
        $latitude  = [];
        $longitude = [];
        $percentage = 0;

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $unique_order_id = substr(str_shuffle(str_repeat($pool, 5)), 0, 6);

        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

        //setting timezone from id
        $tz = new Timezone();
        $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

        //save task images on s3 bucket
        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
                $s3filePath = '/assets/' . $folder . '/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                array_push($images, $path);
            }
            $last = implode(",", $images);
        }

        //create new customer for task or get id of old customer

        if (!isset($request->ids)) {
            $customer = Customer::where('email', '=', $request->email)->first();
            if (isset($customer->id)) {
                $cus_id = $customer->id;
            } else {
                $cus = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'dial_code' => $request->dialCode,
                ];
                $customer = Customer::create($cus);
                $cus_id = $customer->id;
            }
        } else {
            $cus = [
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'dial_code' => $request->dialCode,
            ];
            $customerupdate = Customer::where('id', $request->ids)->update($cus);
            $cus_id = $request->ids;
            $customer = Customer::where('id', $request->ids)->first();
        }

        //get pricing rule  for save with every order
        $pricingRule = PricingRule::where('id', 1)->first();

        //here order save code is started

        $settime = isset($request->schedule_time) ? $request->schedule_time : Carbon::now()->toDateTimeString();
        $notification_time = Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC');
        $agent_id          = $request->allocation_type === 'm' ? $request->agent : null;

        $order = [
            'order_number'                    => generateOrderNo(),
            'customer_id'                     => $cus_id,
            'recipient_phone'                 => $request->recipient_phone,
            'Recipient_email'                 => $request->recipient_email,
            'task_description'                => $request->task_description,
            'driver_id'                       => $agent_id,
            'auto_alloction'                  => $request->allocation_type,
            'images_array'                    => $last,
            'order_type'                      => $request->task_type,
            'order_time'                      => $notification_time,
            'status'                          => $agent_id != null ? 'assigned' : 'unassigned',
            'cash_to_be_collected'            => $request->cash_to_be_collected,
            'base_price'                      => $pricingRule->base_price,
            'base_duration'                   => $pricingRule->base_duration,
            'base_distance'                   => $pricingRule->base_distance,
            'base_waiting'                    => $pricingRule->base_waiting,
            'duration_price'                  => $pricingRule->duration_price,
            'waiting_price'                   => $pricingRule->waiting_price,
            'distance_fee'                    => $pricingRule->distance_fee,
            'cancel_fee'                      => $pricingRule->cancel_fee,
            'agent_commission_percentage'     => $pricingRule->agent_commission_percentage,
            'agent_commission_fixed'          => $pricingRule->agent_commission_fixed,
            'freelancer_commission_percentage'=> $pricingRule->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $pricingRule->freelancer_commission_fixed,
            'unique_id'                       => $unique_order_id
        ];

        $orders = Order::create($order);

        //here is task save code is started

        $dep_id = null; // this is used as dependent task id

        foreach ($request->task_type_id as $key => $value) {
            $taskcount++;
            if (isset($request->address[$key])) {
                $loc = [
                   'short_name'  => $request->short_name[$key],
                    'post_code'   => $request->post_code[$key],
                    'flat_no'   => !empty($request->flat_no[$key])? $request->flat_no[$key] : '',
                ];
              //  $Loction = Location::create($loc);
                $Loction = Location::updateOrCreate(
                    ['latitude' => $request->latitude[$key], 'longitude' => $request->longitude[$key], 'address' => $request->address[$key], 'customer_id' => $cus_id],
                    $loc
                );
                $loc_id = $Loction->id;
                $send_loc_id = $loc_id;
            } else {
                if ($key == 0) {
                    $loc_id = $request->old_address_id;
                    $send_loc_id = $loc_id;
                } else {
                    $loc_id = $request->input('old_address_id' . $key);
                    $send_loc_id = $loc_id;
                }
            }

            $location = Location::where('id', $loc_id)->first();
            if ($key == 0) {
                $finalLocation = $location;
            }

            array_push($latitude, $location->latitude);
            array_push($longitude, $location->longitude);

            $task_appointment_duration = empty($request->appointment_date[$key]) ? '0' : $request->appointment_date[$key];

            $data = [
                'order_id'                   => $orders->id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'appointment_duration'       => $task_appointment_duration,
                'dependent_task_id'          => $dep_id,
                'task_status'                => $agent_id != null ? 1 : 0,
                'created_at'                 => $notification_time,
                'assigned_time'              => $notification_time,
                'barcode'                    => $request->barcode[$key],
                'quantity'                   => $request->quantity[$key],
                'alcoholic_item'             => !empty($request->alcoholic_item[$key])? $request->alcoholic_item[$key] : '',
            ];
            $task = Task::create($data);
            $dep_id = $task->id;
        }

        //accounting for task duration distanse

        $getdata = $this->GoogleDistanceMatrix($latitude, $longitude);
        $paid_duration = $getdata['duration'] - $pricingRule->base_duration;
        $paid_distance = $getdata['distance'] - $pricingRule->base_distance;
        $paid_duration = $paid_duration < 0 ? 0 : $paid_duration;
        $paid_distance = $paid_distance < 0 ? 0 : $paid_distance;
        $total         = $pricingRule->base_price + ($paid_distance * $pricingRule->distance_fee) + ($paid_duration * $pricingRule->duration_price);

        if (isset($agent_id)) {
            $agent_details = Agent::where('id', $agent_id)->first();
            if ($agent_details->type == 'Employee') {
                $percentage = $pricingRule->agent_commission_fixed + (($total / 100) * $pricingRule->agent_commission_percentage);
            } else {
                $percentage = $pricingRule->freelancer_commission_fixed + (($total / 100) * $pricingRule->freelancer_commission_percentage);
            }
        }

        //update order with order cost details

        $updateorder = [
            'actual_time'        => $getdata['duration'],
            'actual_distance'    => $getdata['distance'],
            'order_cost'         => $total,
            'driver_cost'        => $percentage,

         ];

        Order::where('id', $orders->id)->update($updateorder);


        //task tages save code is here

        if (isset($request->allocation_type) && $request->allocation_type === 'a') {
            if (isset($request->team_tag)) {
                $orders->teamtags()->sync($request->team_tag);
            }
            if (isset($request->agent_tag)) {
                $orders->drivertags()->sync($request->agent_tag);
            }
        }

        //this function is called when allocation type is Accept/Reject it find the current task location belongs to which geo fence

        $geo = null;
        if ($request->allocation_type === 'a') {
            $geo = $this->createRoster($send_loc_id);
            $agent_id = null;
        }

        // task schdule code is hare
        $allocation = AllocationRule::where('id', 1)->first();
        if ($request->task_type != 'now') {
            //setting timezone from id
            $tz = new Timezone();
            $timezonee = $tz->timezone_name(Auth::user()->timezone);


            $beforetime = (int)$auth->getAllocation->start_before_task_time;
            //$to = new \DateTime("now", new \DateTimeZone(isset(Auth::user()->timezone)? Auth::user()->timezone : 'Asia/Kolkata') );
            $to = new \DateTime("now", new \DateTimeZone($timezonee));
            $sendTime = Carbon::now();
            $to = Carbon::parse($to)->format('Y-m-d H:i:s');
            $from = Carbon::parse($notification_time)->format('Y-m-d H:i:s');
            $datecheck = 0;
            $to_time = strtotime($to);
            $from_time = strtotime($from);
            if ($to_time >= $from_time) {
                return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
            }

            $diff_in_minutes = round(abs($to_time - $from_time) / 60);
            $schduledata = [];
            if ($diff_in_minutes > $beforetime) {
                $finaldelay = (int)$diff_in_minutes - $beforetime;
                $time = Carbon::parse($sendTime)
                ->addMinutes($finaldelay)
                ->format('Y-m-d H:i:s');

                $schduledata['geo']               = $geo;
                $schduledata['notification_time'] = $time;
                $schduledata['agent_id']          = $agent_id;
                $schduledata['orders_id']         = $orders->id;
                $schduledata['customer']          = $customer;
                $schduledata['finalLocation']     = $finalLocation;
                $schduledata['taskcount']         = $taskcount;
                $schduledata['allocation']        = $allocation;
                $schduledata['database']          = $auth;

                scheduleNotification::dispatch($schduledata)->delay(now()->addMinutes($finaldelay));
                return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
            }
        }


        //this is roster create accounding to the allocation methed

        if ($request->allocation_type === 'a' || $request->allocation_type === 'm') {
            switch ($allocation->auto_assign_logic) {
                case 'one_by_one':
                    //this is called when allocation type is one by one
                    $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                case 'send_to_all':
                    //this is called when allocation type is send to all
                    $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                case 'round_robin':
                    //this is called when allocation type is round robin
                    $this->roundRobin($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                default:
                    //this is called when allocation type is batch wise
                    $this->batchWise($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
            }
        }
        return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
    }

    public function createRoster($send_loc_id)
    {
        $getletlong = Location::where('id', $send_loc_id)->first();
        $lat = $getletlong->latitude;
        $long = $getletlong->longitude;
        return $check = $this->findLocalityByLatLng($lat, $long);
    }

    public function findLocalityByLatLng($lat, $lng)
    {
        // get the locality_id by the coordinate //
        $latitude_y = $lat;
        $longitude_x = $lng;
        $localities = Geo::all();
        if (empty($localities)) {
            return false;
        }

        foreach ($localities as $k => $locality) {

            if(!empty($locality->polygon)){
                $geoLocalitie = Geo::where('id', $locality->id)->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $lat . " " . $lng . ")'))")->first();
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

                foreach ($temp_array as $k => $v) {
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

    public function finalRoster($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation)
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
        $order_details = Order::find($orders_id);

        if($order_details->auto_alloction !='m'){
            if ($type == 'acceptreject') {
                $allcation_type = 'AR';
            } elseif ($type == 'acknowledge') {
                $allcation_type = 'ACK';
            } else {
                $allcation_type = 'N';
            }
        }else{
            $allcation_type = 'ACK';
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

            $geoagents_ids =  DriverGeo::where('geo_id', $geo)->pluck('driver_id');
            $geoagents = Agent::whereIn('id',  $geoagents_ids)->with(['logs','order'=> function ($f) use ($date) {
                $f->whereDate('order_time', $date)->with('task');
            }])->orderBy('id', 'DESC')->get()->where("agent_cash_at_hand", '<', $cash_at_hand);
    

            $totalcount = $geoagents->count();
            $orders = order::where('driver_id', '!=', null)->whereDate('created_at', $date)->groupBy('driver_id')->get('driver_id');

            $allreadytaken = [];
            foreach ($orders as $ids) {
                array_push($allreadytaken, $ids->driver_id);
            }
            $counter = 0;
            $data = [];
            for ($i = 0; $i <= $try-1; $i++) {
                foreach ($geoagents as $key =>  $geoitem) {
                    if (in_array($geoitem->id, $allreadytaken) && !empty($geoitem->device_token) && $geoitem->is_available == 1) {
                        $extra = [
                            'id' => $geoitem->id,
                            'device_type' => $geoitem->device_type, 'device_token' => $geoitem->device_token
                        ];
                        array_push($remening, $extra);
                    } else {
                        if(!empty($geoitem->device_token) && $geoitem->is_available == 1){
                            $data = [
                                'order_id'            => $orders_id,
                                'driver_id'           => $geoitem->id,
                                'notification_time'   => $time,
                                'notification_befor_time' => $rostersbeforetime,
                                'type'                => $allcation_type,
                                'client_code'         => Auth::user()->code,
                                'created_at'          => Carbon::now()->toDateTimeString(),
                                'updated_at'          => Carbon::now()->toDateTimeString(),
                                'device_type'         => $geoitem->device_type??null,
                                'device_token'        => $geoitem->device_token??null,
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
                        Order::where('id', $orders_id)->update(['driver_id'=>$geoitem->id]);

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
            $this->dispatch(new RosterCreate($all, $extraData)); // //this job is for create roster in main database for send the notification  in auto alloction
        }
    }

    //this function for check time diffrence and give a time for notification send between current time and task time

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
    public function SendToAll($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation)
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
        $randem            = rand(11111111, 99999999);
        $rostersbeforetime = $this->checkBeforeTimeDiffrence($notification_time, $beforetime);
        $order_details     = Order::find($orders_id);
        $data = [];

        if($order_details->auto_alloction !='m'){
            if ($type == 'acceptreject') {
                $allcation_type = 'AR';
            } elseif ($type == 'acknowledge') {
                $allcation_type = 'ACK';
            } else {
                $allcation_type = 'N';
            }
        }else{
            $allcation_type = 'ACK';
        }
       // Log::info($allcation_type);
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
                    'client_code'         => Auth::user()->code,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => $randem,
                    'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                ];
                Log::info("if case RosterCreate send to all ");
                $this->dispatch(new RosterCreate($data, $extraData));
            }
        } else {
            
            $geoagents_ids =  DriverGeo::where('geo_id', $geo)->pluck('driver_id');
            $geoagents = Agent::whereIn('id',  $geoagents_ids)->with(['logs','order'=> function ($f) use ($date) {
                $f->whereDate('order_time', $date)->with('task');
            }])->orderBy('id', 'DESC')->get()->where("agent_cash_at_hand", '<', $cash_at_hand);

            for ($i = 0; $i <= $try-1; $i++) {
                foreach ($geoagents as $key =>  $geoitem) {
                    if (!empty($geoitem->device_token) && $geoitem->is_available == 1) {
                        $datas = [
                            'order_id'            => $orders_id,
                            'driver_id'           => $geoitem->id,
                            'notification_time'   => $time,
                            'notification_befor_time' => $rostersbeforetime,
                            'type'                => $allcation_type,
                            'client_code'         => Auth::user()->code,
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $geoitem->device_type??null,
                            'device_token'        => $geoitem->device_token??null,
                            'detail_id'           => $randem,
                            'cash_to_be_collected' => $order_details->cash_to_be_collected??null,
                        ];
                        array_push($data, $datas);
                        if ($allcation_type == 'N' && 'ACK') {Log::info('break');
                            Order::where('id', $orders_id)->update(['driver_id'=>$geoitem->id]);
                            break;
                        }
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
            Log::info("else case send to all ");
            $this->dispatch(new RosterCreate($data, $extraData));
            Log::info("dispatch Done ");
        }
    }

    public function batchWise($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation)
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

        if($order_details->auto_alloction !='m'){
            if ($type == 'acceptreject') {
                $allcation_type = 'AR';
            } elseif ($type == 'acknowledge') {
                $allcation_type = 'ACK';
            } else {
                $allcation_type = 'N';
            }
        }else{
            $allcation_type = 'ACK';
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
            $geoagents_ids =  DriverGeo::where('geo_id', $geo)->pluck('driver_id');
            $geoagents = Agent::whereIn('id',  $geoagents_ids)->with(['logs','order'=> function ($f) use ($date) {
                $f->whereDate('order_time', $date)->with('task');
            }])->orderBy('id', 'DESC')->get()->where("agent_cash_at_hand", '<', $cash_at_hand)->toArray();

            //this function is give me nearest drivers list accourding to the the task location.

            $distenseResult = $this->haversineGreatCircleDistance($geoagents, $finalLocation, $unit, $max_redius, $max_task);

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


    public function roundRobin($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount)
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
        $order_details     = Order::find($orders_id);
        $data = [];

        if($order_details->auto_alloction !='m'){
            if ($type == 'acceptreject') {
                $allcation_type = 'AR';
            } elseif ($type == 'acknowledge') {
                $allcation_type = 'ACK';
            } else {
                $allcation_type = 'N';
            }
        }else{
            $allcation_type = 'ACK';
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
            $geoagents_ids =  DriverGeo::where('geo_id', $geo)->pluck('driver_id');
            $geoagents = Agent::whereIn('id',  $geoagents_ids)->with(['logs','order'=> function ($f) use ($date) {
                $f->whereDate('order_time', $date)->with('task');
            }])->orderBy('id', 'DESC')->get()->where("agent_cash_at_hand", '<', $cash_at_hand)->toArray();

            //this function give me the driver list accourding to who have liest task for the current date

            $distenseResult = $this->roundCalculation($geoagents, $finalLocation, $unit, $max_redius, $max_task);

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

    public function roundCalculation($geoagents, $finalLocation, $unit, $max_redius, $max_task)
    {
        $extraarray = [];
        foreach ($geoagents as $item) {
            $count = isset($item['order']) ? count($item['order']):0;
            if (($max_task > $count) && !empty($item['device_token']) && $item['is_available'] == 1) {
                $data = [
                    'driver_id'    =>  $item['id'],
                    'device_type'  =>  $item['device_type'],
                    'device_token' =>  $item['device_token'],
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

    public function haversineGreatCircleDistance($geoagents, $finalLocation, $unit, $max_redius, $max_task)
    {
        // convert from degrees to radians
        $earthRadius = 6371;  // earth radius in km
        $latitudeFrom  = $finalLocation->latitude;
        $longitudeFrom = $finalLocation->longitude;
        $lastarray     = [];
        $extraarray    = [];
        foreach ($geoagents as $item) {
            $latitudeTo  = $item['logs']['lat']??'';
            $longitudeTo = $item['logs']['long']??'';
            if (!empty($latitudeFrom) && !empty($latitudeFrom) && !empty($latitudeTo) && !empty($longitudeTo) && !empty($latitudeTo) && !empty($longitudeTo) && !empty($item['device_token']) && $item['is_available'] == 1) {
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo   = deg2rad($latitudeTo);
                $lonTo   = deg2rad($longitudeTo);
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

                $final = round($angle * $earthRadius);
                $count = isset($item['order']) ? count($item['order']):0;
                if ($unit == 'metric') {
                    if ($final <= $max_redius && $max_task > $count) {
                        $data = [
                            'driver_id'    =>  $item['logs']['agent_id'],
                            'device_type'  =>  $item['device_type'],
                            'device_token' =>  $item['device_token'],
                            'distance'     =>  $final
                        ];
                        array_push($extraarray, $data);
                    }
                } else {
                    if ($final <= $max_redius && $max_task > $count) {
                        $data = [
                            'driver_id'    =>  $item['logs']['agent_id'],
                            'device_type'  =>  $item['device_type'],
                            'device_token' =>  $item['device_token'],
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

    public function GoogleDistanceMatrix($latitude, $longitude)
    {
        $send   = [];
        $client = ClientPreference::where('id', 1)->first();
        $lengths = count($latitude)-1;
        $value = [];
        $count  = 0;
        $count1 = 1;
        for ($i = 0; $i<$lengths; $i++) {
            $ch = curl_init();
            $headers = array('Accept: application/json',
                    'Content-Type: application/json',
                    );
            $url =  'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$latitude[$count].','.$longitude[$count].'&destinations='.$latitude[$count1].','.$longitude[$count1].'&key='.$client->map_key_1.'';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch); // Close the connection
            $new =   $result;
            array_push($value, $result->rows[0]->elements);
            $count++;
            $count1++;
        }

        if (isset($value)) {
            $totalDistance = 0;
            $totalDuration = 0;
            foreach ($value as $item) {
                //dd($item);
                $totalDistance = $totalDistance + (@$item[0]->distance->value );
                $totalDuration = $totalDuration +(@$item[0]->duration->value);
            }

            if ($client->distance_unit == 'metric') {
                $send['distance'] = round($totalDistance/1000, 2);      //km
            } else {
                $send['distance'] = round($totalDistance/1609.34, 2);  //mile
            }

            $newvalue = round($totalDuration/60, 2);
            $whole = floor($newvalue);
            $fraction = $newvalue - $whole;

            if ($fraction >= 0.60) {
                $send['duration'] = $whole + 1;
            } else {
                $send['duration'] = $whole;
            }
        }

        return $send;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain = '', $id)
    {
        $preference  = ClientPreference::where('id', 1)->first(['theme', 'date_format', 'time_format']);
        $tz = new Timezone();
        $client_timezone = $tz->timezone_name(Auth::user()->timezone);
        $task = Order::with(['customer', 'location', 'taskFirst', 'agent', 'task.location', 'task_rejects.agent'])->find($id);

        $driver_location_logs = [];
        if (!empty($task->driver_id)) {
            $firstTask = $task->task()->first();
            $lastTask = $task->task()->orderBy('id', 'DESC')->first();
            if ($task->status == 'completed') {
                if ($firstTask->id != $lastTask->id) {
                    $order_start_time = $firstTask->assigned_time;
                    $order_end_time = $lastTask->updated_at;
                } else {
                    $order_start_time = $firstTask->assigned_time;
                    $order_end_time = $firstTask->updated_at;
                }
            } else {
                $order_start_time = $firstTask->assigned_time;
                $order_end_time = date('Y-m-d H:i:s');
            }
            $driver_location_log_obj = AgentLog::select('id','lat','long')->where(['agent_id' => $task->driver_id])->whereBetween('created_at',[$order_start_time, $order_end_time])->take(20)->get();
            if(!empty($driver_location_log_obj)){
                foreach($driver_location_log_obj as $log_key => $log){
                    $driver_location_logs[] = array((double)$log->lat, (double)$log->long, $log_key+1);
                }
            }
        }

        return view('tasks/show')->with(['task' => $task, 'client_timezone' => $client_timezone, 'preference' => $preference, 'driver_location_logs' => $driver_location_logs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function edit($domain = '', $id)
    {
        $savedrivertag   = [];
        $saveteamtag     = [];

        $tz = new Timezone();
        $client_timezone = $tz->timezone_name(Auth::user()->timezone);

        $task            = Order::where('id', $id)->with(['task','agent','customer.location'])->first();
        $fatchdrivertag  = TaskDriverTag::where('task_id', $id)->get('tag_id');
        $fatchteamtag    = TaskTeamTag::where('task_id', $id)->get('tag_id');
        if (count($fatchdrivertag) > 0 && count($fatchteamtag) > 0) {
            foreach ($fatchdrivertag as $key => $value) {
                array_push($savedrivertag, $value->tag_id);
            }
            foreach ($fatchteamtag as $key => $value) {
                array_push($saveteamtag, $value->tag_id);
            }
        }
        //for s3 base url
        $can = Storage::disk('s3')->url('image.png');
        $lastbaseurl = str_replace('image.png', '', $can);

        $teamTag   = TagsForTeam::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teamTag = $teamTag->whereHas('assignTeams.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $teamTag = $teamTag->get();


        $agentTag = TagsForAgent::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agentTag = $agentTag->whereHas('assignTags.agent.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $agentTag = $agentTag->get();



       $agents = Agent::orderBy('name', 'asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $agents = $agents->where('is_approved', 1)->get();


        if (isset($task->images_array)) {
            $array = explode(",", $task->images_array);
        } else {
            $array = '';
        }

        $all_locations = array();
        $address_preference  = ClientPreference::where('id', 1)->first(['allow_all_location']);
        if ($address_preference->allow_all_location==1) {
            $cust_id = $task->customer_id;
            $all_locations = Location::where('customer_id', '!=', $cust_id)->where('short_name', '!=', null)->where('location_status', 1)->get();
        }
        $task_proofs = TaskProof::all();
        $preference  = ClientPreference::where('id', 1)->first(['route_flat_input','route_alcoholic_input']);

        $task_locations = Task::where('order_id', $id)->leftJoin('locations', 'tasks.location_id', '=', 'locations.id')
        ->select('tasks.*', 'locations.latitude', 'locations.longitude', 'locations.short_name', 'locations.address')->orderBy('task_order')->get();
        $orderc = Order::where('id', $id)->where('status','completed')->count();
        if($orderc == 0){
            $agent_location = AgentLog::where('agent_id', $task->driver_id)->latest()->first();
        }
        else{
            $agent_location = [];
            $lastElement = $task_locations->last();
            $agent_location['lat']  = $lastElement->latitude;
            $agent_location['lng']  = $lastElement->longitude;
        }
        $task->customer->countrycode = getCountryCode($task->customer->dial_code);
        return view('tasks/update-task')->with(['task' => $task, 'agent_location' => $agent_location, 'task_locations' => $task_locations, 'task_proofs' => $task_proofs, 'preference' => $preference, 'teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'images' => $array, 'savedrivertag' => $savedrivertag, 'saveteamtag' => $saveteamtag, 'main' => $lastbaseurl,'alllocations'=>$all_locations,'client_timezone'=>$client_timezone]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        try {
            DB::beginTransaction();
            $iinputs = $request->toArray();
            $old_address_ids = array();
            foreach ($iinputs as $key => $value) {
                if (substr_count($key, "old_address_id") == 1) {
                    $old_address_ids[] = $key;
                }
            }

            $task_id = Order::find($id);
            $validator = $this->validator($request->all())->validate();
            $loc_id = 0;
            $cus_id = 0;
            $percentage = 0;

            $images = [];
            $last = '';
            $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

            //setting timezone from id
            $tz = new Timezone();
            $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

            if(isset($request->savedFiles) && (count($request->savedFiles) > 0)){
                $update_saved = implode(",", $request->savedFiles);
                $last .= $update_saved;
            }

            if (isset($request->file) && count($request->file) > 0) {
                $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
                $folder = 'client_' . $folder;
                $files = $request->file('file');
                foreach ($files as $key => $value) {
                    $file = $value;
                    $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();

                    $s3filePath = '/assets/' . $folder . '/' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                    array_push($images, $path);
                }
                $file_paths = implode(",", $images);
                if(!empty($last)){
                    $last .= ',' ;
                }
                $last .= $file_paths;
            }

            if (!isset($request->ids)) {
                $customer = Customer::where('email', '=', $request->email)->first();
                if (isset($customer->id)) {
                    $cus_id = $customer->id;
                } else {
                    $cus = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'dial_code' => $request->dialCode,
                    ];
                    $customer = Customer::create($cus);
                    $cus_id = $customer->id;
                }
            } else {
                $cusdata = [
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'dial_code' => $request->dialCode,
                ];
                $customerupdate = Customer::where('id', $request->ids)->update($cusdata);
                
                $cus_id = $request->ids;
                $customer = Customer::where('id', $request->ids)->first();
            }
            $assign = 'unassigned';
            if ($request->allocation_type == 'm') {
                $assign = 'assigned';
            }
            if ($task_id->status == 'completed') {
                $assign = 'completed';
            }

            $pricingRule = PricingRule::where('id', 1)->first();
            
            $agent_id =  isset($request->allocation_type) && $request->allocation_type == 'm' ? $request->agent : null;

            if (isset($agent_id) && $task_id->driver_cost <= 0.00) {
                $agent_details = Agent::where('id', $agent_id)->first();
                if ($agent_details->type == 'Employee') {
                    $percentage = $task_id->agent_commission_fixed + (($task_id->order_cost / 100) * $task_id->agent_commission_percentage);
                } else {
                    $percentage = $task_id->freelancer_commission_fixed + (($task_id->order_cost / 100) * $task_id->freelancer_commission_percentage);
                }
                $this->MassAndEditNotification($id, $agent_id);
            }

            if ($task_id->driver_cost != 0.00) {
                $percentage = $task_id->driver_cost;
            }

            $settime = ($request->task_type=="schedule") ? $request->schedule_time : Carbon::now()->toDateTimeString();
            $notification_time = ($request->task_type=="schedule")? Carbon::parse($settime .' '. $auth->timezone ?? 'UTC')->tz('UTC') : Carbon::now()->toDateTimeString();
            $order = [
                'customer_id'                => $cus_id,
                'recipient_phone'            => $request->recipient_phone,
                'Recipient_email'            => $request->Recipient_email,
                'task_description'           => $request->task_description,
                'driver_id'                  => $agent_id,
                'order_type'                 => $request->task_type,
                'order_time'                 => $notification_time,
                'auto_alloction'             => $request->allocation_type,
                'cash_to_be_collected'       => $request->cash_to_be_collected,
                'call_back_url'              => $request->call_back_url,
                'status'                     => $assign,
                'driver_cost'                => $percentage,
            ];
            $orders = Order::where('id', $id)->update($order);
            if ($last != '') {
                $orderimages = Order::where('id', $id)->update(['images_array' => $last]);
            }

            Task::where('order_id', $id)->delete();
            $dep_id = null;
            foreach ($request->task_type_id as $key => $value) {
                if (isset($request->short_name[$key])) {
                    $loc = [
                        'short_name'    => $request->short_name[$key],
                        'post_code'     => $request->post_code[$key],
                        'flat_no'       => !empty($request->flat_no[$key])? $request->flat_no[$key] : '',
                        'email'         => $request->address_email[$key],
                        'phone_number'  => $request->address_phone_number[$key],
                    ];

                    $Loction = Location::updateOrCreate(
                        ['latitude' => $request->latitude[$key], 'longitude' => $request->longitude[$key], 'address' => $request->address[$key],'customer_id' => $cus_id],
                        $loc
                    );
                    $loc_id = $Loction->id;
                } else {
                    if ($key == 0) {
                        $loc_id = $request->old_address_id;
                    } else {
                        $loc_id = $request->input($old_address_ids[$key]);
                    }

                    $location = Location::where('id', $loc_id)->first();
                    if ($location->customer_id != $cus_id) {
                        $newloc = [
                            'short_name'     => $location->short_name,
                            'post_code'      => $location->post_code,
                            'flat_no'        => $location->flat_no,
                            'alcoholic_item' => $location->alcoholic_item,
                            'email'          => $location->address_email,
                            'phone_number'   => $location->address_phone_number
                        ];
                        $location = Location::updateOrCreate(
                            ['latitude' => $location->latitude, 'longitude' => $location->longitude, 'address' => $location->address, 'customer_id' => $cus_id],
                            $newloc
                        );
                    }
                    $loc_id = $location->id;
                }

                $data = [
                    'order_id'          => $id,
                    'task_type_id'      => $value,
                    'location_id'       => $loc_id,
                    'allocation_type'   => $request->allocation_type,
                    'dependent_task_id' => $dep_id,
                    'task_status'       => isset($agent_id) ? 1 : 0,
                    'barcode'           => $request->barcode[$key],
                    'quantity'          => $request->quantity[$key],
                    'assigned_time'     => $notification_time,
                    'alcoholic_item'    => !empty($request->alcoholic_item[$key])? $request->alcoholic_item[$key] : '',
                ];
                $task = Task::create($data);
                $dep_id = $task->id;
            }

            if (isset($request->allocation_type) && $request->allocation_type === 'a') {
                if (isset($request->team_tag)) {
                    $task_id->teamtags()->sync($request->team_tag);
                }
                if (isset($request->agent_tag)) {
                    $task_id->drivertags()->sync($request->agent_tag);
                }
            }

            //sending silent push notification
            if ($agent_id!="") {
                $allcation_type = 'silent';
                $oneagent = Agent::where('id', $agent_id)->first();
                $notification_data = [
                    'title'               => 'Update Order',
                    'body'                => 'Check All Details For This Request In App',
                    'order_id'            => $id,
                    'driver_id'           => $agent_id,
                    'notification_time'   => Carbon::now()->toDateTimeString(),
                    'type'                => $allcation_type,
                    'client_code'         => Auth::user()->code,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => '',
                ];
                $this->sendsilentnotification($notification_data);
                $orders = Order::where('id', $id)->first();
                if($orders && $orders->call_back_url){
                    $call_web_hook = $this->updateStatusDataToOrder($orders,2,1);  # call web hook when order completed
                }

            }
            $orderdata = Order::select('id', 'order_time', 'status', 'driver_id')->with('agent')->where('id', $id)->first();
            //event(new \App\Events\loadDashboardData($orderdata));
            DB::commit();
            return response()->json(['status' => "Success", 'message' => 'Task Updated successfully!']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => "failure", 'message' => $e->getMessage()]);
        }
    }

    /////////////////// **********************   update status in order panel also **********************************  ///////////////////////
    public function updateStatusDataToOrder($order_details,$dispatcher_status_option_id,$type){
        try {
                $code =  Client::select('id','code')->first();
                $dispatch_traking_url = route('order.tracking',[$code->code,$order_details->unique_id]);
                $client = new GClient(['content-type' => 'application/json']);
                $url = $order_details->call_back_url;
                $dispatch_traking_url = $dispatch_traking_url??'';
                $res = $client->get($url.'?dispatcher_status_option_id='.$dispatcher_status_option_id.'&dispatch_traking_url='.$dispatch_traking_url.'&type='.$type);
                $response = json_decode($res->getBody(), true);
                if($response){
                   // Log::info($response);
                }

        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // this function for sending silent notification
    public function sendsilentnotification($notification_data)
    {
        $new = [];
        array_push($new, $notification_data['device_token']);
        if (isset($new)) {
            fcm()
            ->to($new) // $recipients must an array
            ->data($notification_data)
            ->notification([
                'sound' =>  'default',
            ])
            ->send();

            Log::info('sendsilentnotification');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $orderdata = Order::select('id', 'order_time', 'status', 'driver_id')->with('agent')->where('id', $id)->first();
        Order::where('id', $id)->delete();
        $orderdata->status = "Deleted";
        //event(new \App\Events\loadDashboardData($orderdata));
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function deleteSingleTask(Request $request, $domain = '')
    {
        try {
            $order = Task::find($request->task_id);

            $ordercount = Task::where('order_id',$order->order_id)->count();

            if($ordercount == 1){
                $delorder = Order::where('id',$order->order_id)->delete();
                $route = route('tasks.index');
            }
            else
            {
            $update_dep = Task::where('dependent_task_id',$request->task_id)->update(['dependent_task_id' => $order->dependent_task_id ]);
            $del = Task::where('id',$request->task_id)->delete();
            $route = route('tasks.edit',$order->order_id);
            }

            return response()->json([
                'message' => __('Task Delete Successfully'),
                'count' => $ordercount,
                'url' => $route
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }


    }


    //this is for serch customer for when create tasking

    public function search(Request $request, $domain = '')
    {
        $search = $request->search;
        if (isset($search)) {
            if ($search == '') {
                $employees = Customer::orderby('name', 'asc')->select('id', 'name')->where('status','Active')->limit(10)->get();
            } else {
                $employees = Customer::orderby('name', 'asc')->select('id', 'name')
                                    ->where('status','Active')
                                    ->where('name', 'like', '%' . $search . '%')
                                    ->limit(10)->get();
            }
            $response = array();
            foreach ($employees as $employee) {
                $response[] = array("value" => $employee->id, "label" => $employee->name);
            }

            return response()->json($response);
        } else {
            $id = $request->id;
            $customer = Customer::select('id', 'email', 'phone_number', 'dial_code')->where('id', $id)->where('status', 'Active')->first();
            $customer->countrycode = getCountryCode($customer->dial_code);
            
            $address_preference  = ClientPreference::where('id', 1)->first(['allow_all_location', 'show_limited_address']);
            if ($address_preference->allow_all_location==1) { 
                if($address_preference->show_limited_address ==1 ){
                     // show all address

                $myloctions = Location::where('customer_id', $id)->where('short_name', '!=', null)->where('location_status', 1)->orderBy('short_name','asc')->orderBy('address','asc')->limit(5)->get();
               
                $allloctions = Location::where('customer_id', '!=', $id)->where('short_name', '!=', null)->where('location_status', 1)->orderBy('short_name','asc')->orderBy('address','asc')->limit(5)->get();
                $loction = array_merge($myloctions->toArray(), $allloctions->toArray());
                return response()->json(array('customer'=>$customer, 'location'=>$loction));
                }else{
                    $myloctions = Location::where('customer_id', $id)->where('short_name', '!=', null)->where('location_status', 1)->orderBy('short_name','asc')->orderBy('address','asc')->get();
                    $allloctions = Location::where('customer_id', '!=', $id)->where('short_name', '!=', null)->where('location_status', 1)->orderBy('short_name','asc')->orderBy('address','asc')->get();
                    $loction = array_merge($myloctions->toArray(), $allloctions->toArray());
                    return response()->json(array('customer'=>$customer, 'location'=>$loction));
                }
            } else {
                if($address_preference->show_limited_address ==1 ){
                    
                $loction = Location::where('customer_id', $id)->where('short_name', '!=', null)->where('location_status', 1)->orderBy('short_name','asc')->orderBy('address','asc')->limit(5)->get();
                
            }else{
                    $loction = Location::where('customer_id', $id)->where('short_name', '!=', null)->where('location_status', 1)->orderBy('short_name','asc')->orderBy('address','asc')->get();
                 
                }
                return response()->json(array('customer'=>$customer, 'location'=>$loction));
            }
        }
    }


    //this function is give task list of an order
    public function tasklist($domain = '', $id)
    {
        $task = Order::where('id', $id)->with(['task.location'])->first();
        $client = ClientPreference::where('id', 1)->first();
        $agent = Agent::where('id', $task->driver_id)->first();
        $task = $task->toArray();
        $task['driver_type']   = isset($agent->type) ? $agent->type :'';
        $task['distance_type'] = $client->distance_unit == 'metric' ? 'Km':'Mile';
        return response()->json($task);
    }

    function importCsv(Request $request)
    {
        $fileModel = new csvOrderImport;
        if($request->file('bulk_upload_file')) {
            $fileName = time().'_'.$request->file('bulk_upload_file')->getClientOriginalName();
            $filePath = $request->file('bulk_upload_file')->storeAs('routes', $fileName, 'public');
            $fileModel->name = $fileName;
            $fileModel->path = '/storage/' . $filePath;
            $fileModel->status = 1;
            $fileModel->save();
            $data = Excel::import(new OrderImport($fileModel->id), $request->file('bulk_upload_file'));
            return response()->json([
                'status' => 'Success',
                'message' => 'Route Created successfully!'
            ]);
        }
    }

}
