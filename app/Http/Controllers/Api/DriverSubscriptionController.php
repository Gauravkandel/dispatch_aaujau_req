<?php

namespace App\Http\Controllers\Api;

use DB;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Agent, ClientPreference, Client, Currency, SubscriptionPlansDriver, SubscriptionInvoicesDriver, Payment, PaymentOption};

class DriverSubscriptionController extends BaseController
{
    use ApiResponser;

    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now()->toDateString();
        $preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();
        $sub_plans = SubscriptionPlansDriver::where('status', '1')->where('driver_type', $user->type)->orderBy('id', 'asc')->get();
        $active_subscription = SubscriptionInvoicesDriver::with(['plan'])
            // ->whereNull('cancelled_at')
            ->where('driver_id', $user->id)
            ->where('end_date', '>=', $now )
            ->orderBy('end_date', 'desc')->first();
        
        return response()->json(["status"=>"Success", "data"=>['all_plans'=>$sub_plans, 'subscription'=>$active_subscription, "clientCurrency"=> $preferences->currency ?? NULL]]);
    }

    /**
     * select user subscription.
     * Required Params-
     *  slug
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan(Request $request, $slug = '')
    {
        try{
            $user = Auth::user();
            $preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();
            $previousSubscriptionActive = $this->checkActiveSubscriptionPlan($slug)->getOriginalContent();
            if( $previousSubscriptionActive['status'] == 'Error' ){
                return $this->error($previousSubscriptionActive['message'], 400);
            }
            $sub_plan = SubscriptionPlansDriver::where('slug', $slug)->first();
            if($sub_plan){
                if($sub_plan->status != '1'){
                    return response()->json(["status"=>"Error", "message" => "Subscription plan not active"]);
                }
            }
            else{
                return response()->json(["status"=>"Error", "message" => "Invalid Data"]);
            }
            $code = array('stripe', 'stripe_fpx', 'dpo', 'paystack', 'payfast', 'yoco', 'paylink', 'checkout','kongapay','ccavenue', 'cashfree','easebuzz','vnpay','paytab','toyyibpay','flutterwave','mvodafone','windcave','payphone','stripe_oxxo','viva_wallet', 'mycash','stripe_ideal','openpay','userede');
            $ex_codes = array('cod');
            $payment_options = PaymentOption::select('id', 'code', 'title', 'credentials')->whereIn('code', $code)->where('status', 1)->get();
            foreach ($payment_options as $k => $payment_option) {
                if( (in_array($payment_option->code, $ex_codes)) || (!empty($payment_option->credentials)) ){
                    $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
                    if($payment_option->code == 'stripe'){
                        $payment_option->title = 'Credit/Debit Card (Stripe)';
                    }elseif($payment_option->code == 'kongapay'){
                        $payment_option->title = 'Pay Now';
                    }elseif($payment_option->code == 'mvodafone'){
                        $payment_option->title = 'Vodafone M-PAiSA';
                    }elseif($payment_option->code == 'mobbex'){
                        $payment_option->title = __('Mobbex');
                    }elseif($payment_option->code == 'offline_manual'){
                        $json = json_decode($payment_option->credentials);
                        $payment_option->title = $json->manule_payment_title;
                    }elseif($payment_option->code == 'mycash'){
                        $payment_option->title = __('Digicel MyCash');
                    }elseif($payment_option->code == 'windcave'){
                        $payment_option->title = __('Windcave (Debit/Credit card)');
                    }
                    $payment_option->title = __($payment_option->title);
                    unset($payment_option->credentials);
                }
                else{
                    unset($payment_options[$k]);
                }
            }
            return response()->json(["status"=>"Success", "data"=>["sub_plan" => $sub_plan, "payment_options" => [], "clientCurrency"=> $preferences->currency ?? NULL ]]);
        }
        catch(\Exception $ex){
            return $this->error($ex->getMessage(), 400);
        }
    }

    /**
     * check if user has any active subscription.
     * Required Params-
     *  slug
     *
     * @return \Illuminate\Http\Response
     */
    public function checkActiveSubscriptionPlan($slug = '')
    {
        try{
            $user = Auth::user();
            $now = Carbon::now()->toDateString();
            $userActiveSubscription = SubscriptionInvoicesDriver::with(['plan'])
                                ->whereNull('cancelled_at')
                                ->where('driver_id', $user->id)
                                ->where('end_date', '>=', $now )
                                ->orderBy('end_date', 'desc')->first();
            if( ($userActiveSubscription) && ($userActiveSubscription->plan->slug != $slug) ){
                return $this->error('You cannot buy two subscriptions at the same time', 400);
            }
            return $this->success($userActiveSubscription, 'Processing...');
        }
        catch(\Exception $ex){
            return $this->error($ex->getMessage(), 400);
        }
    }

    /**
     * buy user subscription.
     * Required Params-
     *  slug
     *  payment_option_id
     *  transaction_id
     *  amount
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request, $slug = '')
    {
        try{
            $validator = Validator::make($request->all(), [
                // 'amount'            => 'required|not_in:0',
                // 'transaction_id'    => 'required',
                // 'payment_option_id' => 'required',
            ]);
            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    return $this->error($error_value[0], 400);
                }
            }
            DB::beginTransaction();
            $user = Auth::user();
            $subscription_plan = SubscriptionPlansDriver::where('slug', $slug)->where('status', '1')->first();
            if( ($user) && ($subscription_plan) ){
                $last_subscription = SubscriptionInvoicesDriver::with(['plan'])
                    ->where('driver_id', $user->id)
                    ->where('subscription_id', $subscription_plan->id)
                    ->orderBy('end_date', 'desc')->first();
                $subscription_invoice = new SubscriptionInvoicesDriver;
                $subscription_invoice->driver_id = $user->id;
                $subscription_invoice->subscription_id = $subscription_plan->id;
                $subscription_invoice->slug = strtotime(Carbon::now()).'_'.$slug;
                // $subscription_invoice->status_id = 2;
                $subscription_invoice->frequency = $subscription_plan->frequency;
                $subscription_invoice->driver_type = $subscription_plan->driver_type;
                $subscription_invoice->driver_commission_fixed = $subscription_plan->driver_commission_fixed;
                $subscription_invoice->driver_commission_percentage = $subscription_plan->driver_commission_percentage;
                // $subscription_invoice->payment_option_id = $request->payment_option_id;
                
                $now = Carbon::now();
                $current_date = $now->toDateString();
                $start_date = $current_date;
                $next_date = NULL;
                $end_date = NULL;

                if($user->wallet){
                    $wallet_balance = $user->balanceFloat;
                    if($wallet_balance < $subscription_plan->price){
                        return $this->error(__('Please recharge yout wallet to buy this subscription'), 400);
                    }
                }else{
                    return $this->error(__('Wallet is not active. Please contact administrator'), 400);
                }

                $transactionID = generateUniqueTransactionID();
                $wallet_transaction = $user->wallet->withdrawFloat($subscription_plan->price, [
                    'type' => 'subscription',
                    'transaction_type' => 'subscription_purchase',
                    'transaction_id' => $transactionID,
                    'subscription_slug' => $subscription_plan->slug,
                    'description' => 'Debited by purchasing subscription ('.$subscription_plan->title.')',
                ]);

                // update previous cancelled subscription end date
                $userActiveSubscription = SubscriptionInvoicesDriver::whereNotNull('cancelled_at')->where('driver_id', $user->id)->where('end_date', '>=', $current_date )->orderBy('end_date', 'desc')->first();
                if( $userActiveSubscription ){
                    $previous_sub_end_date = Carbon::now()->subDays(1)->toDateString();
                    $userActiveSubscription->end_date = $previous_sub_end_date;
                    $userActiveSubscription->update();
                }

                if($last_subscription){
                    if($last_subscription->end_date >= $current_date){
                        $start_date = Carbon::parse($last_subscription->end_date)->addDays(1)->toDateString();
                    }
                }
                if($subscription_plan->frequency == 'weekly'){
                    $end_date = Carbon::parse($start_date)->addDays(6)->toDateString();
                }elseif($subscription_plan->frequency == 'monthly'){
                    $end_date = Carbon::parse($start_date)->addMonths(1)->subDays(1)->toDateString();
                }elseif($subscription_plan->frequency == 'yearly'){
                    $end_date = Carbon::parse($start_date)->addYears(1)->subDays(1)->toDateString();
                }
                $next_date = Carbon::parse($end_date)->addDays(1)->toDateString();
                $subscription_invoice->start_date = $start_date;
                $subscription_invoice->next_date = $next_date;
                $subscription_invoice->end_date = $end_date;
                $subscription_invoice->transaction_reference = $transactionID;
                $subscription_invoice->wallet_transaction_id = $wallet_transaction->id;
                $subscription_invoice->subscription_amount = $subscription_plan->price;
                $subscription_invoice->save();
                $subscription_invoice_id = $subscription_invoice->id;
                if($subscription_invoice_id){
                    // $payment = new Payment;
                    // $payment->balance_transaction = $subscription_plan->price;
                    // $payment->transaction_id = $request->transaction_id;
                    // $payment->user_subscription_invoice_id = $subscription_invoice_id;
                    // $payment->date = Carbon::now()->format('Y-m-d');
                    // $payment->save();

                    $message = 'Your subscription has been activated successfully.';
                    DB::commit();
                    $user->wallet->refreshBalance();
                    return $this->success('', $message);
                }
                else{
                    DB::rollback();
                    return $this->error('Error in purchasing subscription.', 400);
                }
            }
            else{
                return $this->error('Invalid Data', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(), 400);
        }
    }

    /**
     * cancel user subscription.
     * Required Params-
     *  slug
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan($slug = '')
    {
        try{
            DB::beginTransaction();
            $active_subscription = SubscriptionInvoicesDriver::with('plan')
                                ->where('slug', $slug)
                                ->where('driver_id', Auth::user()->id)
                                ->orderBy('end_date', 'desc')->first();
            if($active_subscription){
                $active_subscription->cancelled_at = $active_subscription->end_date;
                $active_subscription->updated_at = Carbon::now()->toDateTimeString();
                $active_subscription->save();
                DB::commit();
                return $this->success('', 'Your '.$active_subscription->plan->title.' subscription has been cancelled successfully');
            }
            else{
                return $this->error('Unable to cancel subscription', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(), 400);
        }
    }
}
