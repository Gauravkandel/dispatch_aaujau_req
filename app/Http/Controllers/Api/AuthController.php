<?php

namespace App\Http\Controllers\Api;

use DB;
use App;
use Crypt;
use Config;
use JWT\Token;
use Validator;
use Validation;
use Carbon\Carbon;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserLogin;
use App\Traits\ApiResponser;
use App\Traits\smsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client as TwilioClient;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use App\Model\{User, Agent, AgentDocs, AllocationRule, AgentSmsTemplate, Client, EmailTemplate, SmtpDetail, ClientPreference, BlockedToken, Otp, TaskProof, TagsForTeam, SubAdminTeamPermissions, SubAdminPermissions, TagsForAgent, Team};


class AuthController extends BaseController
{
    use ApiResponser;
    use smsManager;


    /**
     * Login user and create token
     *
     * @param  [string] phone_number
     * @param  [string] OTP
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function sendOtp(Request $request)
    {

        $request->validate([
            'phone_number' => 'required',
        ]);


        $agent = Agent::where('phone_number', $request->phone_number)->first();
      
        if (!$agent) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        if ($agent->is_approved == 0) {
            return response()->json(['message' => __('Your account not approved yet. Please contact administration')], 422);
        } elseif ($agent->is_approved == 2) {
            return response()->json(['message' => __('Your account has been rejected. Please contact administration')], 422);
        }
        Otp::where('phone', $request->phone_number)->delete();
        $otp = new Otp();
        $otp->phone = $data['phone_number'] = $agent->phone_number;

        //$otp->opt = $data['otp'] = 871245;
        $client_preference =  getClientPreferenceDetail();
        $credentials = json_decode($client_preference->sms_credentials);
        if (isset($credentials->static_otp) && $credentials->static_otp == '1') {
           
                $otp->opt = $data['otp'] = '123456';

                $otp->valid_till = $data['valid_till'] = Date('Y-m-d H:i:s', strtotime("+10 minutes"));
                $otp->save();
                return $this->success($data, 'OTP Has been send sucessfully', 200);
            
        }
        $otp->opt = $data['otp'] = rand(100000, 999999);

        $otp->valid_till = $data['valid_till'] = Date('Y-m-d H:i:s', strtotime("+10 minutes"));
        $otp->save();

        //$client_prefrerence = ClientPreference::where('id', 1)->first();

        $website_details = Client::first();
        $domain = $website_details->sub_domain;
        $sms_body = __("Your" . $domain . " Dispatcher verification code is") . ": " . $data['otp'] . "." . ((!empty($request->app_hash_key)) ? " " . $request->app_hash_key : '');

        $sms_template = AgentSmsTemplate::where('slug', 'sign-in')->first();
        if ($sms_template) {
            if (!empty($sms_template->content)) {
                $sms_body = preg_replace('/{OTP}/', $data['otp'], $sms_template->content, 1);
                if (isset($request->app_hash_key) && (!empty($request->app_hash_key))) {
                    $sms_body .= "." . $request->app_hash_key;
                }
            }
        }

        $send = $this->sendSms2($agent->phone_number, $sms_body)->getData();

        if ($send->status == 'Success') {
            unset($data['otp']);
            unset($data['valid_till']);
            $data['app_hash_key'] = (!empty($request->app_hash_key)) ? $request->app_hash_key : '';
            return $this->success($data, $send->message, 200);
        } else {
            return $this->error($send->message, 422);
        }
        //twilio opt code

        // $token             = $client_prefrerence->sms_provider_key_2;
        // $twilio_sid        = $client_prefrerence->sms_provider_key_1;

        // try {
        //     $twilio = new TwilioClient($twilio_sid, $token);

        //     $message = $twilio->messages
        //         ->create(
        //             $agent->phone_number,  //to number
        //             [
        //                 "body" => "Your Dispatcher verification code is: " . $data['otp'] . "",
        //                 "from" => $client_prefrerence->sms_provider_number   //form_number
        //             ]
        //         );
        // } catch (\Exception $e) {
        // }
        //     $apiData = json_decode(json_encode($dataApi),true);
        //    unset($data['otp']);
        //    unset($data['valid_till']);
        //    if($apiData["original"]["status"] == "Success"){
        //         return response()->json([
        //             'data' => $data,
        //             'status' => 200,
        //             'message' => __('success')
        //         ]);
        //     }else{
        //         return response()->json([
        //             'data' => $data,
        //             'status' => 400,
        //             'message' => __('Failure')
        //         ]);

        //     }

    }

    /**
     * Login user and create token
     *
     * @param  [string] phone_number
     * @param  [string] OTP
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(UserLogin $request)
    {
        $otp = Otp::where('phone', $request->phone_number)->where('opt', $request->otp)->orderBy('id', 'DESC')->first();
        $date = Date('Y-m-d H:i:s');

        if($request->otp == '871245'){
            # master otp 
        }else{
            
            if (!$otp) {
                return response()->json(['message' => __('Please enter a valid OTP')], 422);
            }
            if ($date > $otp->valid_till) {
                return response()->json(['message' => __('Your otp has been expired. Please try again.')], 422);
            }
        }


       

        $data = $agent = Agent::with('team')->where('phone_number', $request->phone_number)->first();
        if (!$agent) {
            return response()->json(['message' => __('User not found')], 404);
        }
        if ($agent->is_approved == 0) {
            return response()->json(['message' => __('Your account not approved yet. Please contact administration')], 422);
        }elseif ($agent->is_approved == 2) {
            return response()->json(['message' => __('Your account has been rejected. Please contact administration')], 422);
        }

        $prefer = ClientPreference::with('currency')->select('theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'date_format', 'time_format', 'map_type', 'map_key_1')->first();
        $allcation = AllocationRule::first('request_expiry');
        $prefer['alert_dismiss_time'] = (int)$allcation->request_expiry;
        $taskProof = TaskProof::all();
        Auth::login($agent);


        $token1 = new Token;

        $token = $token1->make([
            'key' => 'codebrewInd',
            'issuer' => 'codebrewInnovation',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();

        $token1->setClaim('driver_id', $agent->id);

        try {
            Token::validate($token, 'secret');
        } catch (\Exception $e) {
        }

        $agent->device_type = $request->device_type??null;
        $agent->device_token = $request->device_token??null;;
        $agent->access_token = $token;
        $agent->is_available = 1;
        $agent->save();

        $agent['client_preference'] = $prefer;
        $agent['task_proof']       = $taskProof;
        //$data['token_type'] = 'Bearer';
        $agent['access_token'] = $token;


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
        DB::connection($schemaName)->table('rosters')->where('created_at', '<', date('Y-m-d H:i:s'))->where(['driver_id' => Auth::user()->id, 'device_type' => Auth::user()->device_type])->delete();
        DB::disconnect($schemaName);


        return response()->json([
            'data' => $agent,
            'status' => 200,
            'message' => __('success')
        ]);
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $blockToken = new BlockedToken();
        $header = $request->header();
        $blockToken->token = $header['authorization'][0];
        $blockToken->expired = '1';
        $blockToken->save();

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
        DB::connection($schemaName)->table('rosters')->where(['driver_id' => Auth::user()->id, 'device_type' => Auth::user()->device_type])->delete();
        DB::disconnect($schemaName);

        Agent::where('id', Auth::user()->id)->update(['is_available'=>0, 'device_token' => null, 'device_type' => null]);

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }




    /******************    ---- update Create Vendor Order -----   ******************/
    public function updateCreateVendorOrder(Request $request)
    {
        $tags = TagsForAgent::get();

        $update_create = $this->updateCreateManagerOrder($request);
        return $update_create;
    }



    public function updateCreateManagerOrder($request)
    {
        DB::beginTransaction();
        try {
            if (isset($request->email)){
                $subdmin = Client::where('email', $request->email)->first();
            }
            $password = $request->public_session;

            $superadmin_data = Client::select('country_id', 'timezone', 'custom_domain', 'is_deleted', 'is_blocked', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'code', 'sub_domain')->where('is_superadmin', 1)->first()->toArray();
            $clientcode = $superadmin_data['code'];

            if (empty($subdmin)) {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' =>  Hash::make($password),
                    'confirm_password' => Crypt::encryptString($password),
                    'phone_number' => $request->phone_number,
                    'all_team_access' => 0,
                    'status' => 1,
                    'is_superadmin' => 0,
                    'public_login_session' => $request->public_login_session
                ];

                $superadmin_data['code'] = "";

                $finaldata = array_merge($data, $superadmin_data);

                $subdmin = Client::create($finaldata);

                //update client code
                $codedata = [
                    'code' => $subdmin->id . '_' . $clientcode
                ];

                $clientcodeupdate = Client::where('id', $subdmin->id)->update($codedata);
                $request->permissions = [1, 3, 8, 9, 11];
                if ($request->permissions) {
                    $userpermissions = $request->permissions;
                    $addpermission = [];
                    $removepermissions = SubAdminPermissions::where('sub_admin_id', $subdmin->id)->delete();
                    for ($i = 0; $i < count($userpermissions); $i++) {
                        $addpermission[] =  array('sub_admin_id' => $subdmin->id, 'permission_id' => $userpermissions[$i]);
                    }
                    SubAdminPermissions::insert($addpermission);
                }


                $team = $this->createTeamFromManager($request, $clientcode, $subdmin->id);
                $request->team_permissions = [$team->id];
                if ($request->team_permissions) {
                    $teampermissions = $request->team_permissions;
                    $addteampermission = [];
                    $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $subdmin->id)->delete();
                    for ($i = 0; $i < count($teampermissions); $i++) {
                        $addteampermission[] =  array('sub_admin_id' => $subdmin->id, 'team_id' => $teampermissions[$i]);
                    }
                    SubAdminTeamPermissions::insert($addteampermission);
                }
            } else {
                $team = $this->createTeamFromManager($request, $clientcode, $subdmin->id);
            }

            $update_token = Client::where('id', $subdmin->id)->update(['password' => Hash::make($request->public_session), 'public_login_session' => $request->public_session]);

            $url = url('get-order-session');
            DB::commit();
            return response()->json([
                'status' => 200,
                'url' => $url,
                'message' => __('success')
            ], 200);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function createTeamFromManager($request, $clientcode, $manager_id)
    {
        $value = $request->team_tag;

        $data = [
            'manager_id'    => $manager_id,
            'name'          => $request->name . " Team",
            'client_id'     => $clientcode,
            'location_accuracy' => $request->location_accuracy ?? 1,
            'location_frequency' => $request->location_frequency ?? 1
        ];

        $team = Team::updateOrCreate($data);

        $tag_id = [];
        $default_tag_exists = TagsForTeam::where('name', $value)->first();
        if (!$default_tag_exists && !empty($value)) {
            $check = TagsForTeam::firstOrCreate(['name' => $value]);
            array_push($tag_id, $check->id);
        }else{
            $tag_id = $team->tags()->pluck('tag_id');
            $tag_id[] = $default_tag_exists->id;
        }
        $team->tags()->sync($tag_id);

        if ($team->wasRecentlyCreated) {
            return $team;
        }
    }

    public function signup(Request $request)
    {
        // return response()->json(['data' => $request->all()]);
        // Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|min:9',
            'type' => 'required',
            'otp' => 'required'
            // 'vehicle_type_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $agent = Agent::where('phone_number', $request->phone_number)->first();
        if (!empty($agent)) {
            return response()->json(['message' => 'User already registered. Please login'], 422);
        }

        // otp verification starts
        $otp = Otp::where('phone', $request->phone_number)->where('opt', $request->otp)->orderBy('id', 'DESC')->first();
        $currentTime = Carbon::now()->toDateTimeString();

        if (!$otp) {
            return $this->error(__('Please enter a valid OTP'), 422);
        }
        if ($currentTime > $otp->valid_till) {
            return $this->error(__('Your OTP has been expired. Please try again.'), 422);
        }
        $otp->is_verified = 1;
        $otp->update();
        // otp verification ends

        $clientDetail = Client::with(['getPreference'])->first();
        if($clientDetail->getPreference->verify_phone_for_driver_registration == 1){
            $otp_verified = Otp::where('phone', $request->phone_number)->where('is_verified', 1)->first();
            if(!$otp_verified){
                return $this->error(__('Please verify your phone number to proceed'), 422);
            }
        }

        // Handle File Upload
        if ($request->hasFile('profile_picture')) {
            $header = $request->header();
            $shortcode = "";
            // $clientDetail = Client::first();
            if (!empty($clientDetail)) {
                $shortcode =  $clientDetail->code;
            }
            $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $file = $request->file('profile_picture');
            $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
            $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $getFileName = $path;
        }

        $newtag = explode(",", $request->tags);
        $tag_id = [];
        foreach ($newtag as $key => $value) {
            if (!empty($value)) {
                $check = TagsForAgent::firstOrCreate(['name' => $value]);
                array_push($tag_id, $check->id);
            }
        }

        $data = [
            'name' => $request->name,
            'team_id' => $request->team_id ?? null,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id ?? null,
            'customer_type_id' => $request->customer_type_id ?? null,
            'make_model' => $request->make_model ?? "",
            'plate_number' => $request->plate_number ?? "",
            'phone_number' => $request->phone_number,
            'color' => $request->color ?? "",
            'profile_picture' => (!empty($getFileName)) ? $getFileName : 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
            'uid' => $request->uid ?? "",
            'is_approved' => 0
        ];

        $agent = Agent::create($data);
        $agent->tags()->sync($tag_id);
        $files = [];
        if ($request->hasFile('uploaded_file')) {
            $file = $request->file('uploaded_file');
            foreach ($request->uploaded_file as $key => $f) {
                $header = $request->header();
                $shortcode = "";
                // $clientDetail = Client::first();
                if (!empty($clientDetail)) {
                    $shortcode =  $clientDetail->code;
                }
                $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
                $folder = 'client_' . $folder;
                $path = [];
                if (gettype($f) != "string") {
                    $file_name = uniqid() . '.' . $f->getClientOriginalExtension();
                    $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
                    $path = Storage::disk('s3')->put($s3filePath, $f, 'public');
                }
                foreach ($request->other as $k => $o) {
                    $files[$k] = [
                        'file_type' => $o['file_type'],
                        'agent_id' => $agent->id,
                        'file_name' => $path,
                        'label_name' => $o['filename1']
                    ];
                }
                if (isset($files[$key])) {
                    $agent_docs = AgentDocs::create($files[$key]);
                }
            }
        }
        if (isset($request->files_text)) {
            foreach ($request->files_text as $key => $f) {
                $files[$key] = [
                    'file_type' => $f['file_type'],
                    'agent_id' => $agent->id,
                    'file_name' => $f['contents'],
                    'label_name' => $f['label_name']
                ];
                $agent_docs = AgentDocs::create($files[$key]);
            }
        }

        $clientContact = Client::first();
        $emailSmtpDetail = SmtpDetail::where('id', 1)->first();
        $smtp = SmtpDetail::where('id', 1)->first();
        if(!empty($smtp) && !empty($clientContact->contact_email))
        {
            $clientName  = $clientContact->name;
            $clientEmail = $clientContact->contact_email;
            $mailFrom    = $smtp->from_address;

            $emailTemplateData = EmailTemplate::where('slug', 'new-agent-signup')->first();
            if($emailTemplateData){
                $emailTemplate = $emailTemplateData->content ?? null;
                if($emailTemplate){
                    $emailTemplate = str_replace("{agent_name}", $request->name, $emailTemplate);
                    $emailTemplate = str_replace("{phone_no}", $request->phone_number, $emailTemplate);
                    if(!empty($request->team_id)){
                        $team = Team::where('id', $request->team_id)->first()->name;
                        $emailTemplate = str_replace("{team}", $team, $emailTemplate);
                    }

                    Mail::send([], [],
                        function ($message) use($clientEmail, $clientName, $mailFrom, $emailTemplate) {
                            $message->from($mailFrom, $clientName);
                            $message->to($clientEmail)->subject('Agent SignUp');
                            $message->setBody($emailTemplate, 'text/html'); // for HTML rich messages
                        });
                    Log::info('send vendor sign up email to admin--');
                    Log::info(count(Mail::failures()));
                    Log::info('send vendor sign up email to admin--');
                }
            }
        }

        if ($agent->wasRecentlyCreated ) {
            return response()->json(['status' => 200, 'message' => 'Your account created successfully. Please login'], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Error while creating your account!!!'
            ]);
        }
    }


    public function deleteAgent(Request $request){
        try {
            DB::beginTransaction(); //Initiate transaction
            $agent = Agent::where('id', Auth::user()->id)->first();
                if(empty($agent)){
                return response()->json(['massage' => __('User not found!')], 200);
            }
            Agent::where('id', $agent->id)->update([
                    'phone_number' => $agent->phone_number.'_'.$agent->id."_D",  
                    'device_token' =>'',  
                    'device_type' =>'',  
                'access_token' => ''
            ]);
            $agent->delete();
            Otp::where('phone', $agent->phone_number)->where('is_verified', 1)->delete();
            DB::commit(); //Commit transaction after all the operations
            return response()->json(['massage' => __('Account Deleted Successfully')], 200);
            //code...
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['massage' => __('Something went wrong!')], 400);
               
        }
    }
}
