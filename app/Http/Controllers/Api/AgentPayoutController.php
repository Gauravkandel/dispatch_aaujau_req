<?php

namespace App\Http\Controllers\Api;
use DB;
use Auth;
use Session;
use Validator;
use DataTables;
use Carbon\Carbon;
// use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
// use App\Http\Traits\ToasterResponser;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\OrderVendorListExport;
use App\Http\Controllers\Api\BaseController;
use App\Traits\agentEarningManager;
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout, AgentBankDetail, AgentConnectedAccount};

class AgentPayoutController extends BaseController{
    use ApiResponser;
    // use ToasterResponser;
    public $gateway;
    public $currency;

    public function __construct(){
        // $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        // if($stripe_creds){
        //     $creds_arr = json_decode($stripe_creds->credentials);
        //     $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        //     $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;
        //     $this->gateway = Omnipay::create('Stripe');
        //     $this->gateway->setApiKey($api_key);
        //     $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        // }

    }

    public function agentPayoutRequestCreate(Request $request, $id){
        try{
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|gt:0',
                'payout_option_id' => 'required'
            ],[
                'payout_option_id.required' => 'Payout option field is required'
            ]);
    
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 422);
            }

            DB::beginTransaction();
            $agent = Agent::where('id',$id);
            // $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
            $user = Auth::user();
            $agent = $agent->first();
            $agent_id = $agent->id;
            //-----------------Code modified by Surendra Singh--------------------------//
            $pending_payout_value  = AgentPayout::where('agent_id', $agent_id)->whereIn('status', [0])->sum('amount');
            $available_funds = agentEarningManager::getAgentEarning($agent_id, 1) - $pending_payout_value;
            //-------------------------------------------------------------------------//
            if($request->amount > $available_funds){
                return $this->error(__('Payout amount is greater than available funds'), 402);
            }

            $preferences = ClientPreference::select('currency_id')->where('id', 1)->first();

            $pay_option = $request->payout_option_id ?? 1;
            if($pay_option == 4){

                $validator = Validator::make($request->all(), [
                    'beneficiary_name' => 'required',
                    'beneficiary_bank_name' => 'required',
                    'beneficiary_account_number' => 'required',
                    'beneficiary_ifsc' => 'required'
                ]);
        
                if ($validator->fails()) {
                    return $this->error($validator->errors()->first(), 422);
                }

                $beneficiary_name = $request->beneficiary_name;
                // $beneficiary_address = $request->beneficiary_address;
                $bank_account = $request->beneficiary_account_number;
                $beneficiary_bank_name = $request->beneficiary_bank_name;
                $bank_ifsc = $request->beneficiary_ifsc;
                $agent_bank_account = AgentBankDetail::where('beneficiary_account_number', $bank_account)->where('beneficiary_ifsc', $bank_ifsc)->where('status', 1)->orderBy('id', 'desc')->first();
                if($agent_bank_account){
                    $agent_bank_account->beneficiary_name = $beneficiary_name;
                    $agent_bank_account->beneficiary_account_number = $bank_account;
                    $agent_bank_account->beneficiary_ifsc = $bank_ifsc;
                    // $agent_bank_account->beneficiary_address = $beneficiary_address;
                    $agent_bank_account->beneficiary_bank_name = $beneficiary_bank_name;
                    $agent_bank_account->status = 1;
                    $agent_bank_account->update();
                }
                else{
                    
                    // find any other account of current agent and inactive that account
                    $get_agent_existing_account = AgentBankDetail::where('agent_id', $agent_id)->where('status', 1)->orderBy('id', 'desc')->first();
                    if($get_agent_existing_account){
                        $get_agent_existing_account->status = 0;
                        $get_agent_existing_account->update();
                    }

                    $agent_bank_account = new AgentBankDetail();
                    $agent_bank_account->agent_id = $agent_id;
                    $agent_bank_account->payout_option_id = $pay_option;
                    $agent_bank_account->beneficiary_name = $beneficiary_name;
                    $agent_bank_account->beneficiary_account_number = $bank_account;
                    $agent_bank_account->beneficiary_ifsc = $bank_ifsc;
                    // $agent_bank_account->beneficiary_address = $beneficiary_address;
                    $agent_bank_account->beneficiary_bank_name = $beneficiary_bank_name;
                    $agent_bank_account->status = 1;
                    $agent_bank_account->save();
                }
            }

            $payout = new AgentPayout();
            $payout->agent_id = $id;
            $payout->payout_option_id = $pay_option;
            $payout->transaction_id = ($pay_option != 1) ? $request->transaction_id : '';
            $payout->amount = $request->amount;
            $payout->currency = $preferences->currency_id;
            $payout->requested_by = $agent->id;
            $payout->status = 0;
            if($pay_option == 4){
                $payout->agent_bank_detail_id = $agent_bank_account->id;
            }
            $payout->save();
            DB::commit();
            return $this->success('', __('Payout is created successfully'), 201);
        }
        catch(Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }


    /**   show agent payout tab details   */
    public function agentPayoutDetails(Request $request)
    {
        $page     = $request->has('page') ? $request->page : 1;
        $limit    = $request->has('limit') ? $request->limit : 30;
        $user     = Auth::user();
        $agent    = Agent::where('id',$user->id)->first();
        //----------------------------------code modified by surendra Singh-------------------//
        $agent_id = $agent->id;

        $client_preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();
       
        $agent_payout_list  = AgentPayout::select('*','status as status_id')->with('payoutOption')->where('agent_id', $agent_id)->orderBy('id','desc')->paginate($limit, $page);
        $past_payout_value  = AgentPayout::where('agent_id', $agent_id)->whereIn('status', [1])->sum('amount');
        $pending_payout_value  = AgentPayout::where('agent_id', $agent_id)->whereIn('status', [0])->sum('amount');

        
        $available_funds    = agentEarningManager::getAgentEarning($agent->id, 1) - $pending_payout_value;
        
        $available_funds    = number_format($available_funds, 2, '.', ',');
        $past_payout_value  = number_format($past_payout_value, 2, '.', ',');

        //-------------------------------------------------------------------------------------//
        // Payout Options start
        // $code = array('cash', 'razorpay', 'bank_account_m_india');
        // $payout_options = PayoutOption::whereIn('code', $code)->where('status', 1)->get(['id', 'code', 'title', 'credentials', 'off_site']);
        // foreach($payout_options as $option){
        //     $creds_arr = json_decode($option->credentials);
        //     if($option->code == 'stripe'){
        //         $option->title = 'Credit/Debit Card (Stripe)';
        //     }
        //     elseif($option->code == 'razorpay'){
        //         $option->api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        //     }
        //     unset($option->credentials);
        //     $option->title = __($option->title);
        // }
        // Payout Options end


        // get agent payout connect details
        $payout_options = $this->payoutConnectDetails($agent_id);


        $data = array(
            'agent' => $agent, 
            'payout_options' => $payout_options,
            'client_preferences' => $client_preferences,
            'agent_payout_list' => $agent_payout_list,
            'past_payout_value' => $past_payout_value, 
            'available_funds' => $available_funds
        );

        return $this->success($data, __('Success'), 201);
    }


    /**   get agent payout bank details  */
    public function agentBankDetails(Request $request)
    {
        $user = Auth::user();
        $agent = Agent::where('id',$user->id)->first();
        $agent_id = $agent->id;

        $client_preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();
        $agent_bank_details = AgentBankDetail::with(['payoutOption'])->where('agent_id', $agent_id)->where('status', 1)->orderBy('id','desc')->first();
        
        $data = array(
            'agent' => $agent, 
            'client_preferences' => $client_preferences,
            'agent_bank_details' => $agent_bank_details
        );

        return $this->success($data, __('Success'), 201);
    }

    
    // Driver payout connect details

    public function payoutConnectDetails($driver)
    {
        $client = Client::where('id', '>', 0)->first();
        if(isset($client->custom_domain) && !empty($client->custom_domain) && $client->custom_domain != $client->sub_domain){
            $server_url =  "https://" . $client->custom_domain . '/';
        }else{
            $server_url =  "https://" . $client->sub_domain . env('SUBDOMAIN') . '/';
        }

        //stripe connected account details
        $codes = ['cash', 'stripe', 'pagarme', 'bank_account_m_india'];
        $payout_creds = PayoutOption::whereIn('code', $codes)->where('status', 1)->get();
        if ($payout_creds) {
            foreach ($payout_creds as $creds) {
                $creds_arr = json_decode($creds->credentials);
                if($creds->code != 'cash'){
                    if ($creds->code == 'stripe') {
                        $creds->stripe_connect_url = '';
                        if( (isset($creds_arr->client_id)) && !empty($creds_arr->client_id) ){
                            $stripe_redirect_url = $server_url."client/verify/oauth/token/stripe";
                            $creds->stripe_connect_url = 'https://connect.stripe.com/oauth/v2/authorize?response_type=code&state='.$driver.'&client_id='.$creds_arr->client_id.'&scope=read_write&redirect_uri='.$stripe_redirect_url;
                        }
                    }

                    // Check if agent has connected account
                    $checkIfStripeAccountExists = AgentConnectedAccount::where(['agent_id' => $driver, 'payment_option_id' => $creds->id])->first();
                    if($checkIfStripeAccountExists && (!empty($checkIfStripeAccountExists->account_id))){
                        $creds->is_connected = 1;
                    }else{
                        $creds->is_connected = 0;
                    }
                }
            }
        }

        // $ex_countries = ['INDIA'];

        // if((!empty($payout_creds->credentials)) && ($client_id != '') && (!in_array($client->country->name, $ex_countries))){
        //     $stripe_redirect_url = 'http://local.myorder.com/client/verify/oauth/token/stripe'; //$server_url."client/verify/oauth/token/stripe";
        //     $stripe_connect_url = 'https://connect.stripe.com/oauth/v2/authorize?response_type=code&state='.$id.'&client_id='.$client_id.'&scope=read_write&redirect_uri='.$stripe_redirect_url;
        // }else{
        //     $stripe_connect_url = route('create.custom.connected-account.stripe', $id);
        // }

        return $payout_creds;
    }
}
