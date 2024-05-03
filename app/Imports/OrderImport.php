<?php

namespace App\Imports;
use DB;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\{BaseController, TaskController};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController1;
use App\Jobs\RosterCreate;
use App\Models\RosterDetail;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Jobs\scheduleNotification;
use App\Model\{Order, Client, ClientPreference, Customer, Location, category, AllocationRule, order_category, Geo, Roster, Agent, DriverGeo, Task, csvOrderImport, Timezone, SubscriptionInvoicesDriver, PricingRule};
use App\Traits\getLocationServices;
use GuzzleHttp\Client as GClient;

class OrderImport implements ToCollection
{
    private $folderName = 'routes';
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, getLocationServices;

    public function  __construct($csv_order_import_id){
        $code = Client::orderBy('id','asc')->value('code');
        $this->csv_order_import_id = $csv_order_import_id;
        $this->folderName = '/'.$code.'/routes';
    }
    
    public function collection(Collection $rows){
        try {
            DB::beginTransaction();
            $i = 0;
            $data = array();
            $error = array();

            $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

            //setting timezone from id
            $tz = new Timezone();
            $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

            try {
                
                    foreach ($rows as $row) 
                    {
                        $checker = 0;
                        $latitude_arr   = [];
                        $longitude_arr  = [];
                        $address_arr    = [];
                        $house_no_arr   = [];
                        $postalcode_arr = [];
                        if ($row[0] != "Customer Name")
                        { 
                            if ($row[0] == "") {
                                $error[] = "Row " . $i . " : Customer Name is empty";
                                $checker = 1;
                            }

                            if ($row[1] == "") {
                                $error[] = "Row " . $i . " : Customer Email is empty";
                                $checker = 1;
                            }

                            if ($row[2] == "") {
                                $error[] = "Row " . $i . " : Customer Country Code is empty";
                                $checker = 1;
                            }

                            if ($row[3] == "") {
                                $error[] = "Row " . $i . " : Customer Mobile No. is empty";
                                $checker = 1;
                            }

                            if ($row[4] == "") {
                                $error[] = "Row " . $i . " : Pickup Address is empty";
                                $checker = 1;
                            }

                            if ($row[7] == "") {
                                $error[] = "Row " . $i . " : Drop off Address is empty";
                                $checker = 1;
                            }
                            
                            if ($row[4] != "") {
                                $latlong = $this->getLatLong($row[4]);
                                $latitude = !empty($latlong['latitude'])?$latlong['latitude']:'';
                                $longitude = !empty($latlong['longitude'])?$latlong['longitude']:'';
                                if($latitude =='' && $longitude == '')
                                {
                                    $error[] = "Row " . $i . " : Pickup Address is not valid";
                                    $checker = 1;
                                }else{
                                    $latitude_arr[]   = $latitude;
                                    $longitude_arr[]  = $longitude;
                                    $address_arr[]    = $row[4];
                                    $house_no_arr[]   = $row[5];
                                    $postalcode_arr[] = $row[6];
                                }
                            }

                            if ($row[7] != "") {
                                $latlong = $this->getLatLong($row[7]);
                                $latitude = !empty($latlong['latitude'])?$latlong['latitude']:'';
                                $longitude = !empty($latlong['longitude'])?$latlong['longitude']:'';
                                if($latitude =='' && $longitude == '')
                                {
                                    $error[] = "Row " . $i . " : Drop off Address is not valid";
                                    $checker = 1;
                                }else{
                                    $latitude_arr[]   = $latitude;
                                    $longitude_arr[]  = $longitude;
                                    $address_arr[]    = $row[7];
                                    $house_no_arr[]   = $row[8];
                                    $postalcode_arr[] = $row[9];
                                }
                            }

                            if ($checker == 0) {
                                $data[] = array('excel_data'=>$row, 'latitude_arr' => $latitude_arr, 'longitude_arr' => $longitude_arr, 'address_arr' => $address_arr, 'house_no_arr' => $house_no_arr, 'postalcode_arr' => $postalcode_arr);
                            }
                        }
                        $i++;
                    }
                    if (!empty($data)) {
                        foreach ($data as $da) {
                            
                            $customer = Customer::where('email', '=', $da['excel_data'][1])->first();
                            if (!empty($customer)) {
                                $cus_id = $customer->id;
                            } else {
                                $cus = [
                                    'name' => $da['excel_data'][0],
                                    'email' => $da['excel_data'][1],
                                    'phone_number' => $da['excel_data'][3],
                                    'dial_code' => $da['excel_data'][2]
                                ];
                                $customer = Customer::create($cus);
                                $cus_id = $customer->id;
                            }
                            
                            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $unique_order_id = substr(str_shuffle(str_repeat($pool, 5)), 0, 6);
                            $settime = ($da['excel_data'][13]!='') ? str_replace("/", "-", $da['excel_data'][13]).' '.(($da['excel_data'][14]!='')?$da['excel_data'][14]:'00:00:00') : Carbon::now()->toDateTimeString();
                            $order_type = ($da['excel_data'][13]!='') ? 'schedule' :'now';
                            $notification_time = ($order_type=="schedule")? Carbon::parse($settime.' '. $auth->timezone ?? 'UTC')->tz('UTC') : Carbon::now()->toDateTimeString();
                            
                            $pricingRule = PricingRule::where('id', 1)->first();
                            $getdata = $this->GoogleDistanceMatrix($da['latitude_arr'], $da['longitude_arr']);
                            $paid_duration = $getdata['duration'] - $pricingRule->base_duration;
                            $paid_distance = $getdata['distance'] - $pricingRule->base_distance;
                            $paid_duration = $paid_duration < 0 ? 0 : $paid_duration;
                            $paid_distance = $paid_distance < 0 ? 0 : $paid_distance;
                            $total         = $pricingRule->base_price + ($paid_distance * $pricingRule->distance_fee) + ($paid_duration * $pricingRule->duration_price);

                            $agent_commission_fixed = $pricingRule->agent_commission_fixed;
                            $agent_commission_percentage = $pricingRule->agent_commission_percentage;
                            $freelancer_commission_fixed = $pricingRule->freelancer_commission_fixed;
                            $freelancer_commission_percentage = $pricingRule->freelancer_commission_percentage;
                            $percentage = 0;

                            if(!empty($da['excel_data'][12])) 
                            {
                                $agent_details = Agent::where('id', $da['excel_data'][12])->first();
                                if (!empty($agent_details)) 
                                {
                                    if ($agent_details->type == 'Employee') 
                                    {
                                        $percentage = $agent_commission_fixed + (($total / 100) * $agent_commission_percentage);
                                    } else {
                                        $percentage = $freelancer_commission_fixed + (($total / 100) * $freelancer_commission_percentage);
                                    }

                                    $now = Carbon::now()->toDateString();
                                    $driver_subscription = SubscriptionInvoicesDriver::where('driver_id', $agent_details->id)->where('end_date', '>', $now)->orderBy('end_date', 'desc')->first();
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
                                    $allocation_type = 'm';
                                    $agentid = $agent_details->id;
                                    $order_status = 'assigned';
                                }else{
                                    $allocation_type = 'a';
                                    $agentid = NULL;
                                    $order_status = 'unassigned';
                                }
                            }else{
                                $allocation_type = 'a';
                                $agentid = NULL;
                                $order_status = 'unassigned';
                            }
                            //---------------------------Route Creation----------------------------------
                            $order = [
                                'order_number'                    => generateOrderNo(),
                                'customer_id'                     => $cus_id,
                                'recipient_phone'                 => $da['excel_data'][2],
                                'Recipient_email'                 => $da['excel_data'][1],
                                'task_description'                => $da['excel_data'][10],
                                'auto_alloction'                  => $allocation_type,
                                'driver_id'                       => $agentid,
                                'images_array'                    => '',
                                'order_type'                      => $order_type,
                                'order_time'                      => $notification_time,
                                'status'                          => $order_status,
                                'unique_id'                       => $unique_order_id,
                                'cash_to_be_collected'            => $da['excel_data'][11],
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
                                'net_quantity'                    => 0
                            ];
                            
                            $orders = Order::create($order);

                            for($i = 0; $i < count($da['address_arr']); $i++)
                            {
                                $latitude  = $da['latitude_arr'][$i];
                                $longitude = $da['longitude_arr'][$i];

                                $loc = [
                                    'post_code'      => $postalcode_arr[$i],
                                    'flat_no'        => $house_no_arr[$i],
                                    'email'          => $da['excel_data'][1],
                                    'phone_number'   => $da['excel_data'][3]
                                ];
                
                                $Loction = Location::updateOrCreate(
                                    ['latitude' => $latitude, 'longitude' => $longitude, 'address' => $da['address_arr'][$i], 'customer_id' => $cus_id], $loc
                                );

                                $loc_id = $Loction->id;
                                //----------------------------------creating task-----------------------
                                $data = [
                                    'order_id'                   => $orders->id,
                                    'task_type_id'               => $i + 1,
                                    'location_id'                => $loc_id,
                                    'dependent_task_id'          => null,
                                    'task_status'                => 0,
                                    'created_at'                 => $notification_time,
                                    'assigned_time'              => $notification_time,
                                ];
                                $task = Task::create($data);
                            }
                        }
                    }
                } catch(\Exception $ex){
                    $error[] = "Other: " .$ex->getMessage();
                    \Log::info($ex->getMessage()."".$ex->getLine());
                }
                $order_csv = csvOrderImport::where('id', $this->csv_order_import_id)->first();
                if (!empty($error)) {
                    $order_csv->status = 3;
                    $order_csv->error = json_encode($error);
                }else{
                    $order_csv->status = 2;
                }
                $order_csv->save();
                DB::commit();
        }catch(\Exception $ex){
            $error[] = "Other: " .$ex->getMessage();
            \Log::info($ex->getMessage()."".$ex->getLine());
            DB::rollback();
        }
    }


    

    
    
}
