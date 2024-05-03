<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\{BaseController, WalletController};
use App\Model\{Agent, Payment, PaymentOption, PayoutOption, Client, ClientPreference, AgentConnectedAccount};

class StripeGatewayController extends BaseController{

    use ApiResponser;
    public $gateway;
    public $currency;
    public $currency_id;
    public $payout_secret_key;
    public $payout_client_id;

    public function __construct()
    {
        $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = isset($stripe_creds->credentials) ? json_decode($stripe_creds->credentials) : null;
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode(true); //set it to 'false' when go live

        $payout_creds = PayoutOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $payout_creds_arr = isset($stripe_creds->credentials) ? json_decode($payout_creds->credentials) : null;
        $this->payout_secret_key = (isset($payout_creds_arr->secret_key)) ? $payout_creds_arr->secret_key : '';
        $this->payout_client_id = (isset($payout_creds_arr->client_id)) ? $payout_creds_arr->client_id : '';

        $primaryCurrency = ClientPreference::with('currency')->select('currency_id')->where('id', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
        $this->currency_id = (isset($primaryCurrency->currency->id)) ? $primaryCurrency->currency->id : '';
    }


    // Verify and store connected account
    public function verifyOAuthToken(request $request)
    {
        try{
            $user = Auth::user();
            $driver = $request->state;
            $returnUrl = url('payment/gateway/connect/response?gateway=stripe');
            $returnType = 'error';
            if($request->has('code')){
                $code = $request->code;
                $checkIfExists = AgentConnectedAccount::where('agent_id', $driver)->first();
                if($driver > 0){
                    if($checkIfExists){
                        $msg = __('You are already connected to stripe');
                        $returnParams = '&status=0';
                    }
                    else{
                        // Complete the connection and get the account ID
                        \Stripe\Stripe::setApiKey($this->payout_secret_key);
                        $response = \Stripe\OAuth::token([
                            'grant_type' => 'authorization_code',
                            'code' => $code,
                        ]);

                        // Access the connected account id in the response
                        $connected_account_id = $response->stripe_user_id;

                        $connectdAccount = new AgentConnectedAccount();
                        $connectdAccount->agent_id = $driver;
                        $connectdAccount->account_id = $connected_account_id;
                        $connectdAccount->payment_option_id = 2;
                        $connectdAccount->status = 1;
                        $connectdAccount->save();

                        $msg = __('Stripe connect has been enabled successfully');
                        $returnType = 'success';
                        $returnParams = '&status=200&ac_id='.$connected_account_id;
                    }
                }else{
                    $msg = __('Invalid Data');
                }
            }
            else{
                $msg = __('Stripe connect has been declined');
            }
            $returnUrl = $returnUrl . $returnParams;
            return Redirect::to($returnUrl)->with($returnType, $msg);
        }
        catch(Exception $ex){
            return Redirect::to(url('payment/gateway/connect/response?gateway=stripe&status=0'))->with('error', $ex->getMessage());
        }
    }

    public function AgentPayoutViaStripe(request $request, $domain='')
    {
        try{
            $user = Auth::user();
            $connected_account = AgentConnectedAccount::where('agent_id', $request->agent_id)->first();
            if($connected_account && (!empty($connected_account->account_id))){

                // $stripe = new \Stripe\StripeClient($this->payout_secret_key);
                // $payment_intent = $stripe->paymentIntents->create([
                //     'payment_method_types' => ['card'],
                //     'amount' => $request->amount * 100,
                //     'currency' => 'INR',
                //     'transfer_data' => [
                //       'destination' => $connected_account->account_id,
                //     ],
                // ]);
                // $charge_id = $payment_intent->id;

                // $response = $stripe->transfers->create([
                //     'amount' => $request->amount * 100,
                //     'currency' => 'USD', //$this->currency
                //     // 'source_transaction' => $charge_id,
                //     'destination' => $connected_account->account_id,
                //     // 'transfer_group' => $charge_id,
                // ]);
                
                $amount = $request->amount; //getDollarCompareAmount($request->amount, $this->currency_id);
                \Stripe\Stripe::setApiKey($this->payout_secret_key);

                // Create a PaymentIntent:
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $amount * 100,
                    'currency' => $this->currency,
                    'payment_method_types' => ['card'],
                    'on_behalf_of' => $connected_account->account_id,
                    'transfer_group' => 'driver_payout',
                ]);
                
                // Create a Transfer to a connected account (later):
                $transfer = \Stripe\Transfer::create([
                    'amount' => $amount * 100,
                    'currency' => $this->currency,
                    'destination' => $connected_account->account_id,
                    'transfer_group' => 'driver_payout',
                ]);
                $transactionReference = $transfer->balance_transaction;
                return $this->success($transactionReference, 'Payout is completed successfully', 200);

            }else{
                return $this->error('You are not connected to stripe', 400);
            }
        }catch(\Exception $ex){
            return $this->error($ex->getMessage(), 400);
        }
    }
}
