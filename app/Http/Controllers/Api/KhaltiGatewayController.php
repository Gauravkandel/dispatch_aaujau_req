<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\{BaseController, WalletController};
use App\Model\{Agent, Payment, PaymentOption, Client, ClientPreference, ClientCurrency};

class KhaltiGatewayController extends BaseController
{
    use ApiResponser;
    public $API_KEY;
    public $API_SECRET_KEY;
    public $test_mode;
    public $api;

    public function __construct()
    {
        $khalti_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'khalti')->where('status', 1)->first();
        $creds_arr = json_decode($khalti_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $api_secret_key = (isset($creds_arr->api_secret_key)) ? $creds_arr->api_secret_key : '';
        $this->test_mode = (isset($khalti_creds->test_mode) && ($khalti_creds->test_mode == '1')) ? true : false;

        $this->API_KEY = $api_key;
        $this->API_SECRET_KEY = $api_secret_key;
        //$this->api = new Api($api_key, $api_secret_key);
    }

    public function khaltiPurchase(Request $request){
        try{
            $user = Auth::user();
            $amount = $request->amount;
            $action = isset($request->action) ? $request->action : '';
            $params = '?amount=' . $amount . '&auth_token='.$user->access_token.'&action='.$action;
            if(($action == 'cart') || ($action == 'tip')){
                $params = $params . '&order=' . $request->order_number;
            }
            elseif($action == 'subscription'){
                $params = $params . '&subscription_id=' . $request->subscription_id;
            }
            return $this->success(url($request->serverUrl.'payment/webview/khalti'.$params));
        }
        catch(Exception $ex){
            Log::info($ex->getMessage());
            return $this->error(__('Server Error', $ex->getCode()));
        }
    }

}
