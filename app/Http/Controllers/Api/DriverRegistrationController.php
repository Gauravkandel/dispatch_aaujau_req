<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Agent, AgentDocs, AgentSmsTemplate, ClientPreference, DriverRegistrationDocument, TagsForAgent, AgentsTag, Team, Otp, Client};

class DriverRegistrationController extends BaseController
{
    use ApiResponser;

    /**
     * create token to register driver
     *
     * @param  [string] phone_number
     * @param  [string] dial_code
     */
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
                'dial_code' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 422);
            }
            $phone = '+' . $request->dial_code . $request->phone_number;
            $agent = Agent::where('phone_number', $phone)->first();

            if ($agent) {
                return response()->json([
                    'message' => __('Phone number already exists')
                ], 404);
            }
            $otp_verified = Otp::where('phone', $phone)->where('is_verified', 1)->first();
            if(!$otp_verified){

                Otp::where('phone', $phone)->delete();
                $otp = new Otp();
                $otp->phone = $phone;

                //static OTP code here by sheikh sohail
                $client_preference =  getClientPreferenceDetail();
                $credentials = json_decode($client_preference->sms_credentials);
                if (isset($credentials->static_otp) && $credentials->static_otp == '1') {

                        $otp->opt = $data['otp'] = '123456';

                        $otp->valid_till = $data['valid_till'] = Date('Y-m-d H:i:s', strtotime("+10 minutes"));
                        $otp->save();
                        return $this->success($data, 'OTP Has been send sucessfully', 200);

                }
                $otp->opt = rand(111111, 999999);
                $newDateTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $otp->valid_till = $newDateTime;
                $otp->save();

                $to = $otp->phone;
                $website_details = Client::first();
                $domain = $website_details->sub_domain;
                $body = "Dear customer,Your ".$domain." OTP for registration is " . $otp->opt;

                $sms_template = AgentSmsTemplate::where('slug', 'sign-up')->first();
                if($sms_template){

                    if(!empty($sms_template->content)){
                        $body = preg_replace('/{OTP}/', $otp->opt, $sms_template->content, 1);
                        if(isset($request->app_hash_key) && (!empty($request->app_hash_key))){
                            $body .= ".".$request->app_hash_key;
                        }
                    }
                }

                $send = $this->sendSms2($to, $body)->getData();
                if ($send->status == 'Success') {
                    return $this->success([], $send->message, 200);
                } else {
                    return $this->error($send->message, 422);
                }
            }else{
                return $this->success($otp_verified, __('Phone number has already been verified'), 200);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * verify token to register driver
     *
     * @param  [string] phone_number
     * @param  [string] dial_code
     * @param  [string] OTP
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
                'dial_code' => 'required',
                'otp' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 422);
            }
            $phone = '+' . $request->dial_code . $request->phone_number;
            $agent = Agent::where('phone_number', $phone)->first();
            if ($agent) {
                return $this->error(__('Phone number already exists'), 404);
            }

            $otp = Otp::where('phone', $phone)->where('opt', $request->otp)->orderBy('id', 'DESC')->first();
            $currentTime = Carbon::now()->toDateTimeString();

            if (!$otp) {
                return $this->error(__('Please enter a valid OTP'), 422);
            }
            if ($currentTime > $otp->valid_till) {
                return $this->error(__('Your OTP has been expired. Please try again.'), 422);
            }
            $otp->is_verified = 1;
            $otp->update();
            return $this->success($otp, __('Phone number has been verified'), 200);
        }
        catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }


    //
    public function validator(array $data)
    {


        $full_number = '';
        if (isset($data['country_code']) && !empty($data['country_code']) && isset($data['phone_number']) && !empty($data['phone_number']))
            $full_number = '+' . $data['country_code'] . $data['phone_number'];

        $data['phone_number'] = '+' . $data['country_code'] . $data['phone_number'];
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
           // 'vehicle_type_id' => ['required'],
            //'make_model' => ['required'],
            //'plate_number' => ['required'],
            'phone_number' =>  ['required', 'min:9', 'max:15', Rule::unique('agents')->where(function ($query) use ($full_number) {
                return $query->where('phone_number', $full_number);
            })],
            //'color' => ['required'],
            'upload_photo' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
        ]);
    }

    public function storeAgent(Request $request)
    {
        try {
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    return response()->json(['status' => 0, "message" => $error_value[0]]);
                }
            }
            $getFileName = null;
            if ($request->hasFile('upload_photo')) {
                $header = $request->header();
                if (array_key_exists("shortcode", $header)) {
                    $shortcode =  $header['shortcode'][0];
                }
                $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
                $folder = 'client_' . $folder;
                $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
                $folder = 'client_' . $folder;
                $file = $request->file('upload_photo');
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
                'type' => $request->type,
                'vehicle_type_id' => $request->vehicle_type_id??null,
                'make_model' => $request->make_model,
                'plate_number' => $request->plate_number,
                'phone_number' =>  '+' . $request->country_code . $request->phone_number,
                'color' => $request->color,
                'profile_picture' => $getFileName != null ? $getFileName : 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
                'uid' => $request->uid,
                'is_approved' => 0,
                'team_id' => $request->team_id == null ? $team_id = null : $request->team_id
            ];
            $agent = Agent::create($data);
            $agent->tags()->sync($tag_id);
            $files = [];
            if ($request->hasFile('uploaded_file')) {
                $file = $request->file('uploaded_file');
                foreach ($request->uploaded_file as $key => $f) {
                    $header = $request->header();
                    if (array_key_exists("shortcode", $header)) {
                        $shortcode =  $header['shortcode'][0];
                    }
                    $folder = str_pad($shortcode, 8, '0', STR_PAD_LEFT);
                    $folder = 'client_' . $folder;
                    $path = [];
                    if (gettype($f) != "string") {
                        $file_name = uniqid() . '.' . $f->getClientOriginalExtension();
                        $s3filePath = '/assets/' . $folder . '/agents' . $file_name;
                        $path = Storage::disk('s3')->put($s3filePath, $f, 'public');
                    }
                    foreach (json_decode($request->other) as $k => $o) {
                        $files[$k] = [
                            'file_type' => $o->file_type,
                            'agent_id' => $agent->id,
                            'file_name' => $path,
                            'label_name' => $o->filename1
                        ];
                    }
                    if (isset($files[$key])) {
                        $agent_docs = AgentDocs::create($files[$key]);
                    }
                }
            }
            foreach (json_decode($request->files_text) as $key => $f) {
                $files[$key] = [
                    'file_type' => $f->file_type,
                    'agent_id' => $agent->id,
                    'file_name' => $f->contents,
                    'label_name' => $f->label_name
                ];
                $agent_docs = AgentDocs::create($files[$key]);
            }

            if ($agent->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'message' => __('Thanks for signing up. We will get back to you shortly!'),
                    'data' => $agent
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function sendDocuments()
    {
        try {
            $show_vehicle_type_icon = [];
            $type = ClientPreference::OrderBy('id','desc')->value('custom_mode');
            $types = json_decode($type);

            if(isset($types->show_vehicle_type_icon))
            $show_vehicle_type_icon = explode(',',$types->show_vehicle_type_icon);

            $data['documents'] = DriverRegistrationDocument::orderBy('file_type', 'DESC')->get();
            $data['all_teams'] = Team::OrderBy('id','desc')->get();
            $data['agent_tags'] = TagsForAgent::OrderBy('id','desc')->get();
            $data['vehicle_types'] = ((count($show_vehicle_type_icon)>0)?json_encode($show_vehicle_type_icon):json_encode(['1','2','3','4','5']));
            return response()->json([
                'status' => 200,
                'message' => 'Success!',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
