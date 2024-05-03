<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\ClientPreference;
use App\Model\Currency;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\ProcessClientDatabase;
use App\Model\AgentDocs;
use App\Model\Client;
use App\Model\Cms;
use App\Model\SubClient;
use App\Model\TaskProof;
use App\Model\TaskType;
use App\Model\DriverRegistrationDocument;
use App\Model\{SmtpDetail, SmsProvider, VehicleType};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Session;
use Illuminate\Support\Facades\Storage;
use Crypt;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class ClientController extends Controller
{
    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

    protected function errorResponse($message = null, $code, $data = null)
	{
		return response()->json([
			'status' => 'Error',
			'message' => $message,
			'data' => $data
		], $code);
	}


    private function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while (Client::where('code', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }



    /**
     * Store/Update Client Preferences
     */
    public function storePreference(Request $request, $domain = '', $id)
    {
        $customerDistenceNotification = '';
        if(!empty($request->customer_notification)){
            $data = ['customer_notification_per_distance'=>json_encode($request->customer_notification)];
            ClientPreference::where('client_id', $id)->update($data);

            return redirect()->back()->with('success', 'Preference updated successfully!');
        }

        if($request->has('custom_mode')){
            $customMode['is_hide_customer_notification'] = (!empty($request->custom_mode['is_hide_customer_notification']) && $request->custom_mode['is_hide_customer_notification'] == 'on')? 1 : 0;

            $customMode['hide_subscription_module'] = (!empty($request->custom_mode['hide_subscription_module']) && $request->custom_mode['hide_subscription_module'] == 'on')? 1 : 0;

            $data = ['custom_mode'=>json_encode($customMode)];
            ClientPreference::where('client_id', $id)->update($data);
        
            $customMode['show_vehicle_type_icon'] = implode(',',$request->custom_mode['show_vehicle_type_icon']);
            $data = ['custom_mode'=>json_encode($customMode)];
            ClientPreference::where('client_id', $id)->update($data);
            

            return redirect()->back()->with('success', 'Preference updated successfully!');
        }

        if(!empty($request->fcm_server_key)){
            $data = ['fcm_server_key'=>$request->fcm_server_key];
            ClientPreference::where('client_id', $id)->update($data);

            return redirect()->back()->with('success', 'Preference updated successfully!');
        }

        //Batch Allocation Code
        if($request->has('mybatch')){
            if($request->has('batch_allocation')){
                DB::table('clients')->where('code',$id)->update([
                    'batch_allocation' => 1
                ]);

                $data = [
                    'create_batch_hours'=>$request->create_batch_hours,
                    'maximum_route_per_job'=>$request->maximum_route_per_job,
                    'job_consist_of_pickup_or_delivery'=>$request->has('job_consist_of_pickup_or_delivery')?'1':0
                ];
                ClientPreference::where('client_id', $id)->update($data);
                return redirect()->back()->with('success', 'Preference updated successfully!');
            }else{
                DB::table('royodelivery_db.clients')->where('code',$id)->update([
                    'batch_allocation' => 0
                ]);

                $data = [
                    'create_batch_hours'=>null,
                    'maximum_route_per_job'=>null,
                    'job_consist_of_pickup_or_delivery'=>0
                ];
                ClientPreference::where('client_id', $id)->update($data);
                return redirect()->back()->with('success', 'Preference updated successfully!');
            }
        }

        if($request->has('autopay_submit')){//dd($request->auto_payout);
            $auto_payout = !empty($request->auto_payout)?(($request->auto_payout == "on")?1:0):0;
            $data = ['auto_payout'=>$auto_payout];
            ClientPreference::where('client_id', $id)->update($data);
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }
        
        $client = Client::where('code', $id)->firstOrFail();
        # if submit custom domain by client
        if ($request->custom_domain && $request->custom_domain != $client->custom_domain) {
            try {
                $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $request->custom_domain);
                $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $request->custom_domain);
                $my_url =   $request->custom_domain;

                $data1 = [
                    'domain' => $my_url
                ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "localhost:3000/add_subdomain",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data1),
                    CURLOPT_HTTPHEADER => array(
                       "content-type: application/json",
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                if ($err) {
                    return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $err]));
                }

               // $process = shell_exec("/var/app/Automation/script.sh '".$my_url."' ");
            } catch (Exception $e) {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $e->getMessage()]));
            }


            $connectionToGod = $this->createConnectionToGodDb($id);
            $exists = Client::where('code', '<>', $id)->where('custom_domain', $request->custom_domain)->count();
            if ($exists) {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
            } else {
                Client::where('code', $id)->update(['custom_domain' => $request->custom_domain]);
                $custom_db_name = Client::where('code', $id)->first();
                $connectionToLocal = $this->createConnectionToClientDb($custom_db_name->database_name);
                $dbname = DB::connection()->getDatabaseName();
                if ($dbname != env('DB_DATABASE')) {
                    Client::where('id', '!=', 0)->update(['custom_domain' => $request->custom_domain]);
                }
            }
        }


        # if submit sub_domain domain by client
        if ($request->sub_domain && ($request->sub_domain != $client->sub_domain)) {
            $validator = Validator::make($request->all(), [
                    'sub_domain' => 'required|min:3',
                ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $update_sub_domain = $this->updateSubDomainFromClient($request, $id);
            if ($update_sub_domain == true) {
                $new_domain_link = "http://".$request->sub_domain."".env('SUBDOMAIN', '.royodispatch.com');
                return redirect()->to($new_domain_link);
            } else {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['sub_domain' => 'Sub Domain name "' . $request->sub_domain . '" is not available. Please select a different domain']));
            }
        }

        unset($request['sub_domain']);
        unset($request['custom_domain']);

        if($request->has('sms_provider'))
        {
            if($request->sms_provider == 1) //for twillio
            {

                $sms_credentials = [
                    'sms_from' => $request->sms_from,
                    'sms_key' => $request->sms_key,
                    'sms_secret' => $request->sms_secret,
                ];
                $request->merge([
                    'sms_provider_key_1'=>$request->sms_key,
                    'sms_provider_key_2'=>$request->sms_secret,
                    'sms_provider_number'=>$request->sms_from,
                     ]);
            }elseif($request->sms_provider == 2) // for mTalkz
            {
                $sms_credentials = [
                    'api_key' => $request->mtalkz_api_key,
                    'sender_id' => $request->mtalkz_sender_id,
                ];

            }elseif($request->sms_provider == 3) // for mazinhost
            {
                $sms_credentials = [
                    'api_key' => $request->mazinhost_api_key,
                    'sender_id' => $request->mazinhost_sender_id,
                ];

            }elseif($request->sms_provider == 4) // for unifonic
            {
                $sms_credentials = [
                    'unifonic_app_id' => $request->unifonic_app_id,
                    'unifonic_account_email' => $request->unifonic_account_email,
                    'unifonic_account_password' => $request->unifonic_account_password,
                ];
            }
            elseif($request->sms_provider == 5) // for unifonic
            {
                $sms_credentials = [
                    'api_key' => $request->arkesel_api_key,
                    'sender_id' => $request->arkesel_sender_id,
                ];
            }
            elseif($request->sms_provider == 6) // for unifonic
            {
                $sms_credentials = [
                    'api_key' => $request->aakashsms_api_key,
                ];
            }
            //for static otp
            $sms_credentials['static_otp'] = ($request->has('static_otp') && $request->static_otp == 'on') ? 1 : 0;
         
            $request->merge(['sms_credentials'=>json_encode($sms_credentials)]);
        }

        unset($request['sms_key']);
        unset($request['sms_from']);
        unset($request['sms_secret']);

        unset($request['mtalkz_api_key']);
        unset($request['mtalkz_sender_id']);

        unset($request['mazinhost_api_key']);
        unset($request['mazinhost_sender_id']);

        unset($request['unifonic_app_id']);
        unset($request['unifonic_account_email']);
        unset($request['unifonic_account_password']);

        unset($request['arkesel_api_key']);
        unset($request['arkesel_sender_id']);

        unset($request['aakashsms_api_key']);

        if($request->has('cancel_verify_edit_order_config')){
            $request->request->add(['verify_phone_for_driver_registration' => ($request->has('verify_phone_for_driver_registration') && $request->verify_phone_for_driver_registration == 'on') ? 1 : 0]);
            $request->request->add(['is_edit_order_driver' => ($request->has('is_edit_order_driver') && $request->is_edit_order_driver == 'on') ? 1 : 0]);
            $request->request->add(['is_cancel_order_driver' => ($request->has('is_cancel_order_driver') && $request->is_cancel_order_driver == 'on') ? 1 : 0]);
            $request->request->add(['is_driver_slot' => ($request->has('is_driver_slot') && $request->is_driver_slot == 'on') ? 1 : 0]);
        }

        if($request->has('refer_and_earn')){
            $request->request->add(['reffered_by_amount' => ($request->has('reffered_by_amount') && $request->reffered_by_amount > 0) ? $request->reffered_by_amount : 0]);
            $request->request->add(['reffered_to_amount' => ($request->has('reffered_to_amount') && $request->reffered_to_amount > 0) ? $request->reffered_to_amount : 0]);
        }
        if($request->has('address_limit_order_config')){
            $request->request->add(['show_limited_address' => ($request->has('show_limited_address') && $request->show_limited_address == 'on') ? 1 : 0]);
        }
        
        
        //pr($request->all());
        $updatePreference = ClientPreference::updateOrCreate([
            'client_id' => $id
        ], $request->all());



        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Preference updated successfully!',
                'data' =>    $updatePreference
            ]);
        } else {
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }
    }


    // ************* update Sub Domain From Client ******************************* /////////////////
    public function updateSubDomainFromClient($request, $id)
    {
        $connectionToGod = $this->createConnectionToGodDb($id);
        $exists = Client::where('code', '<>', $id)->where('sub_domain', $request->sub_domain)->count();
        if ($exists || $request->sub_domain == 'api' ||  $request->sub_domain == 'god'  ||  $request->sub_domain == 'godpanel'  ||  $request->sub_domain == 'admin') {
            return false;
        } else {
            Client::where('code', $id)->update(['sub_domain' => $request->sub_domain]);
            $custom_db_name = Client::where('code', $id)->first();
            $connectionToLocal = $this->createConnectionToClientDb($custom_db_name->database_name);
            $dbname = DB::connection()->getDatabaseName();
            if ($dbname != env('DB_DATABASE')) {
                Client::where('id', '!=', 0)->update(['sub_domain' => $request->sub_domain]);
            }
            return true;
        }
    }


    // ************* create connection with god panel database ******************************* /////////////////
    public function createConnectionToGodDb($id)
    {
        $already_db = DB::connection()->getDatabaseName();
        $god_db = env('DB_DATABASE');
        $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $god_db,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];
        Config::set("database.connections.$god_db", $default);
        DB::setDefaultConnection($god_db);
        DB::purge($god_db);
    }

    // ************* create connection with existing db ******************************* /////////////////
    public function createConnectionToClientDb($db_name)
    {
        $database_name = 'db_'.$db_name;
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
        DB::setDefaultConnection($database_name);
        DB::purge($database_name);
    }

    /**
     * Store/Update Client Preferences
     */
    public function ShowPreference()
    {
        $preference  = ClientPreference::where('client_id', Auth::user()->code)->first();
        $currencies  = Currency::orderBy('iso_code')->get();
        $cms         = Cms::all('content');
        $task_proofs = TaskProof::where('type', '!=', 0)->get();
        $task_list   = TaskType::all();
        $user        = Auth::user();
        $client      = Client::where('code', $user->code)->first();
        $subClients  = SubClient::all();
        return view('customize')->with(['clientContact'=>$client, 'preference' => $preference, 'currencies' => $currencies,'cms'=>$cms,'task_proofs' => $task_proofs,'task_list' => $task_list]);
    }

    public function updateContactUs(Request $request){
        $rules = array(
            'contact_phone_number' => 'required|min:7|max:15'
        );
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $user = Auth::user();
        $client = Client::where('code', $user->code)->first();
        $client->contact_address =  $request->contact_address ;
        $client->contact_phone_number =  $request->contact_phone_number ;
        $client->contact_email =  $request->contact_email ;
        $client->save();
        return redirect()->back()->with('success', 'Contact Us Updated successfully!');
    }

    /**
     * Show Configuration page
     */
    public function ShowConfiguration()
    {
        $preference  = ClientPreference::where('client_id', Auth::user()->code)->first();
        $customMode  = json_decode($preference->custom_mode);
        $client      = Auth::user();
        $subClients  = SubClient::all();
        $smtp        = SmtpDetail::where('id', 1)->first();
        $vehicleType = VehicleType::latest()->get();
        $agent_docs=DriverRegistrationDocument::get();
        $smsTypes = SmsProvider::where('status', '1')->get();
        return view('configure')->with(['preference' => $preference, 'customMode' => $customMode, 'client' => $client,'subClients'=> $subClients,'smtp_details'=>$smtp, 'agent_docs' => $agent_docs,'smsTypes'=>$smsTypes,'vehicleType'=>$vehicleType]);
    }



    /**
     * Show Options page
     */
    public function routeCreateConfigure(Request $request, $domain = '', $id)
    {
        $updatePreference = ClientPreference::where('client_id', $id)->update(['route_flat_input' => $request->route_flat_input, 'route_alcoholic_input' => $request->route_alcoholic_input]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Preference updated successfully!',
                'data' =>    $updatePreference
            ]);
        } else {
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }

    }


    /**
     * Show Options page
     */
    public function ShowOptions()
    {
        $preference = ClientPreference::where('client_id', Auth::user()->id)->first();
        return view('options')->with(['preference' => $preference]);
    }

    public function cmsSave(Request $request, $id)
    {
        $cms =  Cms::where('id', $request->id)->first();
        if($cms){
            $cms->update(['content'=>$request->content]);
        }
        return response()->json(true);
    }

    public function taskProof(Request $request)
    {
        $requestAll = $request->all();
        for ($i=1; $i <= 3 ; $i++) {
            $check = TaskProof::where('id', $i)->first();

            if (isset($check)) {
                $update                     = TaskProof::find($i);
            } else {
                $update                     = new TaskProof;
            }

            $update->image              = isset($requestAll['image_'.$i])? 1 : 0 ;
            $update->image_requried     = isset($request['image_requried_'.$i])? 1 : 0 ;
            $update->signature          = isset($request['signature_'.$i])? 1 : 0 ;
            $update->signature_requried = isset($request['signature_requried_'.$i])? 1 : 0 ;
            $update->note               = isset($request['note_'.$i])? 1 : 0 ;
            $update->note_requried      = isset($request['note_requried_'.$i])? 1 : 0 ;
            $update->barcode            = isset($request['barcode_'.$i])? 1 : 0 ;
            $update->barcode_requried   = isset($request['barcode_requried_'.$i])? 1 : 0 ;
            $update->otp                = isset($request['otp_'.$i])? 1 : 0 ;
            $update->otp_requried       = isset($request['otp_requried_'.$i])? 1 : 0 ;
            $update->face               = isset($request['face_'.$i])? 1 : 0 ;
            $update->face_requried      = isset($request['face_requried_'.$i])? 1 : 0 ;
            $update->qrcode             = isset($request['qrcode_'.$i])? 1 : 0 ;
            $update->qrcode_requried    = isset($request['qrcode_requried_'.$i])? 1 : 0 ;
            $update->save();
        }

        return redirect()->route('preference.show')->with('success', 'Preference updated successfully!');
    }

    public function saveSmtp(Request $request)
    {
        $check = SmtpDetail::where('id', 1)->first();

        if (isset($check)) {
            $update                     = SmtpDetail::find(1);
        } else {
            $update                     = new SmtpDetail;
        }

        $update->client_id          = Auth::user()->id;
        $update->driver             = 'smtp';
        $update->host               = $request->host;
        $update->port               = $request->port;
        $update->encryption         = $request->encryption;
        $update->username           = $request->username;
        $update->password           = $request->password;
        $update->from_address       = $request->from_address;

        $update->save();
        return redirect()->route('configure')->with('success', 'Configure updated successfully!');
    }

     public function store(Request $request){
        try {
            $this->validate($request, [
              'name' => 'required|string|max:60',
              'file_type' => 'required',
            ]);
            DB::beginTransaction();
            $driver_registration_document = new DriverRegistrationDocument();
            $driver_registration_document->file_type = $request->file_type;
            $driver_registration_document->name = $request->name;
            $driver_registration_document->is_required = (!empty($request->is_required))?1:0;
            $driver_registration_document->save();
            DB::commit();
            return $this->successResponse($driver_registration_document, getAgentNomenclature().' Registration Document Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        try {
            $driver_registration_document = DriverRegistrationDocument::where(['id' => $request->driver_registration_document_id])->firstOrFail();
            return $this->successResponse($driver_registration_document, '');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DriverRegistrationDocument $driverRegistrationDocument){
         try {
            $this->validate($request, [
              'name' => 'required|string|max:60',
              'file_type' => 'required',
            ]);
            DB::beginTransaction();
            $driver_registration_document_id = $request->driver_registration_document_id;
            $driver_registration_document = DriverRegistrationDocument::where('id', $driver_registration_document_id)->first();
            $driver_registration_document->file_type = $request->file_type;
            $driver_registration_document->name = $request->name;
            $driver_registration_document->is_required = (!empty($request->is_required))?1:0;
            $driver_registration_document->save();

            DB::commit();
            return $this->successResponse($driver_registration_document, getAgentNomenclature().' Registration Document Updated Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    // upload logo
    public function faviconUoload(Request $request){

        $validator = Validator::make($request->all(), [
            'favicon' => ['required']
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update');
        }

        $favicon='';
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $s3filePath = '/assets/Clientfavicon';
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $favicon = $path;
        }
        $preference = ClientPreference::where('client_id', Auth::user()->code)->first();
        if($favicon){
            $preference->favicon = $favicon;
        }
        $preference->save();

        return redirect()->route('configure')->with('success', 'Favicon updated successfully!');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        try {
            DriverRegistrationDocument::where('id', $request->driver_registration_document_id)->delete();

            return $this->successResponse([], getAgentNomenclature().' Registration Document Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }


}
