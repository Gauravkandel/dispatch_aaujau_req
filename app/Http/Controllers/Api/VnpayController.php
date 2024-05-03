<?php

namespace App\Http\Controllers\Api;


use Log;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Api\BaseController;

// use App\Http\Controllers\Api\v1\OrderController;
use App\Model\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax};
use Illuminate\Support\Facades\Auth as FacadesAuth;

class VnpayController extends BaseController
{
    use ApiResponser;
    private $MERCHANT_KEY;
    private $SALT;
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
   
    private $vnp_apiUrl;
    private $startTime;
    private $expire;
   

    public function __construct() {
        $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'vnpay')->where('status', 1)->first();
       
        $json = json_decode($payOpt->credentials);
        $this->vnp_TmnCode = $json->vnpay_website_id ?? null;//"COCOSIN"; //Website ID in VNPAY System
        $this->vnp_HashSecret =  $json->vnpay_server_key ?? null ;//"RAOEXHYVSDDIIENYWSLDIIZTANXUXZFJ"; //Secret key
        if($payOpt->test_mode == 1){
            $this->vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $this->vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        }else{
            // change url for production mode
            $this->vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";//"https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $this->vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        }
       
        //Config input format
        //Expire
        $this->startTime = date("YmdHis");
        $this->expire = date('YmdHis',strtotime('+15 minutes',strtotime( $this->startTime)));
    }

    public function order(Request $request){
       
        $primaryCurrency = ClientPreference::with('currency')->first();
        $language= ($request->hasHeader('language')) ? $request->header('language') : 'en';
        if($primaryCurrency->currency->iso_code != 'VND' ) {
            $error =  __(' Currency format error!');
            return $this->error($error, 400);
        }
        $user = Auth::user();
        // pr($request->all());
        $amount =  $request->amount;
        $vnp_Amount    = $amount * 100;
        
    
        $now = new \DateTime();
        $created_at = $now->format('Y-m-d H:i:s');
        $orderId = $request->order_number ?? generateOrderNo();
        $cart_id  = '';
        //udf1 for payment_form
        //udf2 for user id 
        
        $payment_form = $request->action ?? 'cart';
        $returnUrlParams = '?order_id={order_id}&order_token={order_token}&gateway=vnpay&amount=' . $request->amount . '&payment_form=' . $payment_form;
        $vnp_Returnurl =  url($request->serverUrl.'payment/vnpay/api' . $returnUrlParams);
        $order_info = [
            'payment_form'=>  $payment_form ,
            'user_id'=> auth()->user()->id,
        ];
        $vnp_OrderInfo = json_encode($order_info);

        $vnp_HashSecret =$this->vnp_HashSecret;
        $vnp_TmnCode   = $this->vnp_TmnCode;
        //$vnp_Returnurl = url($request->serverUrl.'payment/vnpay/api' . $returnUrlParams);
        $vnp_TxnRef    = $request->order_number ?? generateOrderNo();// order number 
        $vnp_OrderInfo = $vnp_OrderInfo ;
        $vnp_OrderType = $request->order_type ?? 'billpayment' ;
        $vnp_Amount    = $vnp_Amount ; //"1806000";//
        $vnp_Locale   = ($language == 'en') ? 'en' : 'vn';
        $vnp_BankCode = $request->bank_code ?? null  ;
        $vnp_IpAddr   = $request->ip(); // $_SERVER['REMOTE_ADDR'] ;
        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

         $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" =>  $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $startTime,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => 'other',
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$expire,
        );
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
       
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = $this->vnp_Url ;
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return $this->success($vnp_Url, __('Success'), 201);
       
    }
}