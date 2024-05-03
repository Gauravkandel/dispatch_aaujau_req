<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use \App\Http\Controllers\Api\WalletController;
use App\Model\{Agent, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Transaction};

class KhaltiGatewayController extends BaseController
{
    use ApiResponser;
    public $API_KEY;
    public $API_SECRET_KEY;
    public $test_mode;
    public $api;

    public function __construct()
    {

        
    }

    //Intial Transaction
    public function khaltiVerification(Request $request)
    {
        try {
            $user = Auth::user();
            // $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            // $amount = $this->getDollarCompareAmount($request->amount);
            // $amount = filter_var($amount, FILTER_SANITIZE_NUMBER_INT);

            // $order_number = $request->input('order_id');
            // if (!isset($order_number)) {
            //     $order_number = 0;
            // }
            $data = [
                // 'user_id' 	=> $request->input('user_id'),
                // 'mobile' 	=> $request->input('mobile'),
                'amount' 	=> $request->input('amount')/100,
                'pre_token' => $request->input('token')
                // 'order_id' => $request->input('order_number')
            ];

            $output = $this->verification($data);
            if($output) {

                $data = $request->all();
                $data['order_id'] = $request->input('order_id');
                $data['amount'] = $request->input('amount')/100;
                $data['currency'] = 'NPR';
                $data['payment_from'] = $request->input('payment_form');
                $data['auth_token'] = $request->input('auth_token');

                return $this->success($data, 'Payment Verification in Process');

            }
        } catch (\Exception $ex) {
            \Log::info($ex->getMessage());
            return $this->error('Server Error', 400);
        }

    }

    // Verification after trannsaction
    public function verification($khalti)
    {
        $khalti_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'khalti')->where('status', 1)->first();
        $creds_arr = json_decode($khalti_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $api_secret_key = (isset($creds_arr->api_secret_key)) ? $creds_arr->api_secret_key : '';
        $this->test_mode = (isset($khalti_creds->test_mode) && ($khalti_creds->test_mode == '1')) ? true : false;

        $this->API_KEY = $api_key;
        $this->API_SECRET_KEY = $api_secret_key;
        //$this->api = new Api($api_key, $api_secret_key);

        $args = http_build_query(array(
            'token' => $khalti['pre_token'],
            'amount'  => $khalti['amount']*100
        ));
        $url = "https://khalti.com/api/v2/payment/verify/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key '.$this->API_SECRET_KEY.' '];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $token = json_decode($response, TRUE);
        if (isset($token['idx'])&& $status_code == 200)
        {
            return true;
        }
        return false;
    }

    public function webView(Request $request, $domain='')
    {
        // try{
            // $auth_token = $request->auth_token;
            // $agent = Agent::where('access_token', $auth_token)->first();
            // Auth::login($agent);
            $payment_form = $request->action;
            // $returnParams = 'amount='. $request->amount . '&payment_form=' . $payment_form;
            // if($payment_form == 'cart'){
            //     $returnParams .= '&order='.$request->order;
            // }
            // elseif($payment_form == 'tip'){
            //     $returnParams .= '&order='.$request->order;
            // }
            
            $request->request->add(['payment_form' => $payment_form]);
            $data = $request->all();
            return view('payment_gateway.khalti_view')->with(['data' => $data]);
        // }
        // catch(\Exception $ex){
        //     return redirect()->back()->with('errors', $ex->getMessage());
        // }
    }

    public function khaltiCompletePurchaseApp(Request $request, $domain='')
    {
        try {
            // $user = Auth::user();
            if ($request['status'] == 'Success') {
                $response = [];
                $transactionId = $request['data']['payment_id'];
                $amount = $request['data']['amount'];
                $auth_token = $request['data']['auth_token'];
                if($request['data']['payment_from'] == 'wallet'){
                    $request->request->add(['amount' => $amount, 'transaction_id' => $transactionId, 'auth_token'=>$auth_token, 'payment_option_id'=>17]);
                    $walletController = new WalletController();
                    $res= $walletController->creditAgentWallet($request);
                }
                return $this->success($transactionId, __('Payment has been done successfully'));
            } else {
                return $this->error(__('Payment Failed'), 400);
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
            return $this->error(__('Server Error'), 400);
        }
    }
}
