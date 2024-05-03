<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use Session;
use DataTables;
use Carbon\Carbon;
// use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
// use App\Http\Traits\ToasterResponser;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgentPayoutRequestListExport;
use App\Http\Controllers\{BaseController, StripeGatewayController};
use App\Traits\agentEarningManager;
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout, AgentBankDetail};

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

    public function filter(Request $request){
        $from_date = "";
        $to_date = "";
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
        }
        $agents = Agent::with(['orders' => function($query) use($from_date,$to_date) {
            if((!empty($from_date)) && (!empty($to_date))){
                $query->between($from_date." 00:00:00", $to_date." 23:59:59");
            }
        }])->where('status', '!=', '2')->orderBy('id', 'desc');

        $agents = $agents->get();
        foreach ($agents as $agent) {
            $agent->total_paid = 0.00;
            $agent->view_url = route('agent.show', $agent->id);
            $agent->payable_amount = number_format($agent->orders->sum('order_cost'),2, ".","");
            $agent->admin_commission_amount = number_format($agent->orders->sum('admin_commission_percentage_amount') + $agent->orders->sum('admin_commission_fixed_amount'), 2, ".","");

            $is_stripe_connected = 0;
            // $checkIfStripeAccountExists = AgentConnectedAccount::where('agent_id', $agent->id)->first();
            // if($checkIfStripeAccountExists && (!empty($checkIfStripeAccountExists->account_id))){
            //     $is_stripe_connected = 1;
            // }
            $agent->is_stripe_connected = $is_stripe_connected;

            $agent->agent_earning = number_format($agent->order_value, 2, ".","");
        }
        return Datatables::of($agents)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request){
                        if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                            return true;
                        }
                        return false;
                    });
                }
            })->make(true);
    }

    public function export() {
        return Excel::download(new AgentPayoutRequestListExport, 'agent_payout_requests.csv');
    }


    public function agentPayoutRequests(Request $request)
    {        
        $user = Auth::user();
        $total_order_value = Order::orderBy('id','desc');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', $user->id);
            });
        }
        $total_order_value = $total_order_value->sum('order_cost');

        $pending_payouts = AgentPayout::where('status', 0);
        $completed_payouts = AgentPayout::where('status', 1);
        $failed_payouts = AgentPayout::where('status', 2);
        $pending_payout_value = $pending_payouts->sum('amount');
        $completed_payout_value = $completed_payouts->sum('amount');
        $pending_payout_count = $pending_payouts->count();
        $completed_payout_count = $completed_payouts->count();
        $failed_payout_count = $failed_payouts->count();
        $payout_options = PayoutOption::where('status', 1)->get();
        $preferences = ClientPreference::with('currency')->where('id', 1)->first();
        $currency_symbol = $preferences->currency->symbol ?? '$';
        return view('agent.payout-requests')->with(['total_order_value' => number_format($total_order_value, 2), 'preferences' => $preferences, 'pending_payout_value'=>$pending_payout_value, 'completed_payout_value'=>$completed_payout_value, 'pending_payout_count'=>$pending_payout_count, 'completed_payout_count'=>$completed_payout_count, 'failed_payout_count'=>$failed_payout_count, 'payout_options'=>$payout_options, 'currency_symbol'=>$currency_symbol]);
    }

    public function agentPayoutRequestsFilter(Request $request){
        $client = Client::where('code', Auth::user()->code)->with(['getTimezone', 'getPreference'])->first();
        $from_date = "";
        $to_date = "";
        $user = Auth::user();
        $status = $request->status;
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
        }
        $vendor_payouts = AgentPayout::with(['agent', 'payoutOption', 'payoutBankDetails'=>function($q){
            $q->where('status', 1);
        }])->orderBy('updated_at','desc');
        // if($user->is_superadmin == 0){
        //     $vendor_payouts = $vendor_payouts->whereHas('vendor.permissionToUser', function ($query) use($user) {
        //         $query->where('user_id', $user->id);
        //     });
        // }
        $vendor_payouts = $vendor_payouts->where('status', $status)->get();
        foreach ($vendor_payouts as $payout) {
            $payout->date = convertDateTimeInTimeZone($payout->created_at, $client->getTimezone->timezone);
            $payout->agentName = $payout->agent ? $payout->agent->name : '';
            // $payout->requestedBy = ucfirst($payout->user->name);
            $payout->amount = $payout->amount;
            $payout->type = __($payout->payoutOption->title);
            $payout->bank_account = $payout->agent_bank_detail_id ?? '';
        }
        return Datatables::of($vendor_payouts)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                // if (!empty($request->get('search'))) {
                //     $instance->collection = $instance->collection->filter(function ($row) use ($request){
                //         if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                //             return true;
                //         }
                //         return false;
                //     });
                // }
            })->make(true);
    }

    public function agentPayoutRequestComplete(Request $request, $domain = ''){
        try{
            $user = Auth::user();
            $id = $request->payout_id;
            $payout_option_id = $request->payout_option_id;
          
            $payout = AgentPayout::with(['payoutBankDetails'=> function($q){
                $q->where('status', 1);
            }])->where('id', $id)->first();
          
            $request->request->add(['agent_id' => $payout->agent_id]);
            
            $agent = Agent::where('id', $payout->agent_id)->where('is_approved', 1)->first();
            if(!$agent){
                return Redirect()->back()->with('error', __('This '.getAgentNomenclature().' is not approved!'));
            }
            
            $agent_account = $payout->payoutBankDetails->first() ? $payout->payoutBankDetails->first()->beneficiary_account_number : '';
            $agent_id = $agent->id;
            

            $available_funds = agentEarningManager::getAgentEarning($payout->agent_id, 1);

            if($request->amount > $available_funds){
                return Redirect()->back()->with('error', __('Payout amount is greater than '.getAgentNomenclature().' available funds'));
            }

            $payout_option = '';
            if($payout_option_id > 0){
                $payout_option = PayoutOption::where('id', $payout_option_id)->value('title');
            }

            /////// Payout via stripe ///////
            if($payout_option_id == 2){
                $stripeController = new StripeGatewayController();
                $response = $stripeController->AgentPayoutViaStripe($request)->getData();
                if($response->status != 'Success'){
                    return Redirect()->back()->with('error', __($response->message));
                }
                $request->request->add(['transaction_id' => $response->data]);
            }

            // update payout request
            $request->request->add(['status' => 1]);
            $udpate_response = $this->updateAgentPayoutRequest($request, $payout)->getData();

            if($udpate_response->status == 'Success'){
                $debit_amount = $request->amount;
                $wallet = $agent->wallet;
                if ($debit_amount > 0) {
                    $meta = [
                        'type' => 'payout',
                        'transaction_type' => 'payout_success',
                        'payment_option' => $payout_option,
                        'payout_id' => $payout->id
                    ];
                    if(isset($request->transaction_id)){
                        $meta['transaction_id'] = $request->transaction_id;
                    }
                    $custom_meta = 'Debited for payout request';
                    if($payout_option_id == 4){
                        // $custom_meta = $custom_meta . '<b>XXXX'.substr($agent_account, -4).'</b>';
                        $meta['bank_account'] = $agent_account;
                    }
                    $meta['description'] = $custom_meta;
                    $wallet->forceWithdrawFloat($debit_amount, $meta);
                }
            }
            
            return Redirect()->back()->with('success', __('Payout has been completed successfully'));
        }
        catch(Exception $ex){
            DB::rollback();
            return Redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function updateAgentPayoutRequest($request, $payout=''){
        try{
            DB::beginTransaction();
            $payout->transaction_id = $request->transaction_id ?? null;
            $payout->status = $request->status;
            $payout->update();
            DB::commit();
            return $this->success('', __('Payout has been completed successfully'));
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }

    public function agentPayoutRequestsCompleteAll(Request $request, $domain = ''){
        try{
            DB::beginTransaction();
            $payout_ids = $request->payout_ids;
            if(count($payout_ids) < 1){
                return $this->error(__('Please select any record'), 422);
            }
            foreach($payout_ids as $pay_id){
                $payout = AgentPayout::with(['payoutBankDetails'=> function($q){
                    $q->where('status', 1);
                }])->where('id', $pay_id)->first();
                
                $agent = Agent::where('id', $payout->agent_id)->where('is_approved', 1)->first();
                $credit = $agent->agentPayment->sum('cr');
                $debit = $agent->agentPayment->sum('dr');
                $agent_account = $payout->payoutBankDetails->first() ? $payout->payoutBankDetails->first()->beneficiary_account_number : '';
                $agent_id = $agent->id;

                $total_order_value = Order::where('driver_id', $agent_id)->orderBy('id','desc');
                $total_order_value = $total_order_value->sum('order_cost');

                $agent_payouts = AgentPayout::where('agent_id', $agent_id)->orderBy('id','desc');
                $agent_payouts = $agent_payouts->where('status', 1)->sum('amount');

                $past_payout_value = $agent_payouts;
                $available_funds = $total_order_value + $agent->balanceFloat + $debit - $past_payout_value - $credit;

                if($payout->amount > $available_funds){
                    // return Redirect()->back()->with('error', __('Payout amount is greater than agent available funds'));
                    continue;
                }

                $payout->status = 1;
                $payout->save();

                $debit_amount = $payout->amount;
                $wallet = $agent->wallet;
                if ($debit_amount > 0) {
                    $custom_meta = 'Wallet has been <b>Debited</b> for payout request';
                    if($agent_account != ''){
                        $custom_meta = $custom_meta . '<b>XXXX'.substr($agent_account, -4).'</b>';
                    }
                    $wallet->forceWithdrawFloat($debit_amount, [$custom_meta]);
                }
            }
            
            DB::commit();
            return $this->success('', __('Payout has been completed successfully'), 201);
        }
        catch(Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }

    /**   get agent payout bank details  */
    public function agentPayoutBankDetails(Request $request)
    {
        try{
            $agent_payout_bank_detail_id = $request->id;
            $agent_bank_details = AgentBankDetail::with('agent')->where('id', $agent_payout_bank_detail_id)->first();
            return $this->success($agent_bank_details, __('Success'), 201);
        }
        catch(Exception $ex){
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }

    /**   UPDATE agent payout bank details  */
    /*public function updateagentPayoutBankDetails(Request $request)
    {
        try{
            $agent_payout_bank_detail_id = $request->id;
            $agent_bank_account = AgentBankDetail::where('id', $agent_payout_bank_detail_id)->first();
            if($agent_bank_account){
                $agent_bank_account->beneficiary_name = $request->beneficiary_name;
                $agent_bank_account->beneficiary_account_number = $request->bank_account;
                $agent_bank_account->beneficiary_ifsc = $request->bank_ifsc;
                $agent_bank_account->beneficiary_bank_name = $request->beneficiary_bank_name;
                $agent_bank_account->update();
                return $this->success('', __('Agent bank details are successfully updated'), 201);
            }
            return $this->error(__('Invalid Data'), 422);
        }
        catch(Exception $ex){
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }*/
}
