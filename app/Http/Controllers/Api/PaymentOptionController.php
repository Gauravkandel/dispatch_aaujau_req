<?php

namespace App\Http\Controllers\Api;
use DB;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
// use Omnipay\Omnipay;
use Illuminate\Http\Request;
// use Omnipay\Common\CreditCard;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\{BaseController, RazorpayGatewayController,VnpayController,CcavenueController, KhaltiGatewayController};
use App\Model\{Client, ClientPreference, Agent, PaymentOption};

class PaymentOptionController extends BaseController{
    use ApiResponser;
    public $gateway;

    public function getPaymentOptions(Request $request, $page = ''){
        if($page == 'wallet'){
            $code = array('paypal', 'stripe', 'yoco', 'paylink','razorpay','simplify','square','vnpay','ccavenue', 'khalti');
        }else{
            $code = array('cod', 'paypal', 'payfast', 'stripe', 'mobbex','yoco','paylink','razorpay','gcash','simplify','square');
        }
        $payment_options = PaymentOption::whereIn('code', $code)->where('status', 1)->get(['id', 'code', 'title', 'credentials', 'off_site']);
        foreach($payment_options as $option){
            $creds_arr = json_decode($option->credentials);
            if($option->code == 'stripe'){
                $option->title = 'Credit/Debit Card (Stripe)';
            }
            elseif($option->code == 'razorpay'){
                $option->api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            }
            unset($option->credentials);
            $option->title = __($option->title);
        }
        return $this->success($payment_options, '', 201);
    }

    public function postPayment(Request $request, $gateway = ''){
        if(!empty($gateway)){
            $header = $request->header();
            $client = Client::where('database_name', $header['client'][0])->first();
            $domain = '';
            if(!empty($client->custom_domain)){
                $domain = $client->custom_domain;
            }else{
                $domain = $client->sub_domain.env('SUBDOMAIN');
            }
            $server_url = "https://".$domain."/";
            $request->serverUrl = $server_url;
            $request->currencyId = $request->header('currency');
            $function = 'postPaymentVia_'.$gateway;
            if(method_exists($this, $function)) {
                if(!empty($request->action)){
                    $response = $this->$function($request); // call related gateway for payment processing
                    return $response;
                }
            }
            else{
                return $this->error("Invalid Gateway Request", 400);
            }
        }else{
            return $this->error("Invalid Gateway Request", 400);
        }
    }

    public function postPaymentVia_stripe(Request $request){
        $gateway = new StripeGatewayController();
        return $gateway->stripePurchase($request);
    }

    // public function postPaymentVia_payfast(Request $request){
    //     $gateway = new PayfastGatewayController();
    //     return $gateway->payfastPurchase($request);
    // }

    // public function postPaymentVia_mobbex(Request $request){
    //     $gateway = new MobbexGatewayController();
    //     return $gateway->mobbexPurchase($request);
    // }

    // public function postPaymentVia_yoco(Request $request){
    //     $gateway = new YocoGatewayController();
    //     return $gateway->yocoWebview($request);
    // }

    // public function postPaymentVia_paylink(Request $request){
    //     $gateway = new PaylinkGatewayController();
    //     return $gateway->paylinkPurchase($request);
    // }

    public function postPaymentVia_razorpay(Request $request){
        $gateway = new RazorpayGatewayController();
        return $gateway->razorpayPurchase($request);
    }
    public function postPaymentVia_vnpay(Request $request){
        $gateway = new VnpayController();
        return $gateway->order($request);
    }

    public function postPaymentVia_ccavenue(Request $request){
        $gateway = new CcavenueController();
        return $gateway->paywebView($request);
    }

    public function postPaymentVia_khalti(Request $request){
        $gateway = new KhaltiGatewayController();
        return $gateway->khaltiPurchase($request);
    }

    // public function postPaymentVia_simplify(Request $request){
    //     $gateway = new SimplifyGatewayController();
    //     return $gateway->simplifyPurchase($request);
    // }
    // public function postPaymentVia_square(Request $request){
    //     $gateway = new SquareGatewayController();
    //     return $gateway->squarePurchase($request);
    // }
}
