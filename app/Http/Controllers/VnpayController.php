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

class VnpayController extends BaseController{

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
            //change url for production mode
            $this->vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";//"https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $this->vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        }
       
        //Config input format
        //Expire
        $this->startTime = date("YmdHis");
        $this->expire = date('YmdHis',strtotime('+15 minutes',strtotime( $this->startTime)));
    }


    function order(Request $request){
       
        $primaryCurrency = ClientPreference::with('currency')->first();
        $language= Session::get('applocale');
        
        if($primaryCurrency->currency->iso_code != 'VND' ) {
            $error =  __(' Currency format error!');
            return $this->errorResponse($error, 400);
        }
        $user = Auth::user();
        $amount =  $request->amount;
       
        $vnp_HashSecret =$this->vnp_HashSecret;
        $vnp_TmnCode = $this->vnp_TmnCode;
        // pr($vnp_HashSecret);
       // $vnp_Returnurl =route('vnpay_webview');
        $vnp_TxnRef    = $request->order_number ?? generateOrderNo();// order number 
        $vnp_OrderInfo = $request->order_desc ?? null ;
        $vnp_OrderType = $request->order_type ?? 'billpayment' ;
        $vnp_Amount    = $amount * 100; //"1806000";//
        //pr($vnp_Amount);
        $vnp_Locale   = (($language ?? '' ) == 'en') ? 'en' : 'vn';
        $vnp_BankCode = $request->bank_code ?? null  ;
        $vnp_IpAddr   = $request->ip(); // $_SERVER['REMOTE_ADDR'] ;

        // //Add Params of 2.0.1 Version
        
        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
        $payment_form = $request->payment_form ?? 'wallet';
        $cart_id  = '';
        $returnUrlParams = '?order_id={order_id}&order_token={order_token}&gateway=vnpay&amount=' . $request->amount . '&payment_form=' . $payment_form;
        $vnp_Returnurl =  url('payment/vnpay/api' . $returnUrlParams);
        $order_info = [
            'payment_form'=>  $payment_form ,
            'user_id'=> auth()->user()->id,
            'subscription_id' =>$request->subscription_id ?? '',
            'cart_id' =>$cart_id,
        ];
        $vnp_OrderInfo = json_encode($order_info);
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
       // pr($inputData);
  
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
        
        $response['status']='Success';
        $response['data']=$vnp_Url;
        
       // return $this->successResponse($response, 'Order has been created successfully');
       
       
    }

    function vnpay_respont(Request $request){
       
       pr($request->all());
        $inputData = array();
        $vnp_HashSecret = $this->vnp_HashSecret;
       
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

           
        $order_number = $inputData['vnp_TxnRef'];
        $meta_data = json_decode($inputData['vnp_OrderInfo']);
            
        $cart_id = $meta_data->cart_id ? $request->cart_id : '';
        $payment_form = $meta_data->payment_form;
       
        if($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00' ){
           
            if($payment_form == 'wallet'){
                $returnUrl = route('user.wallet');
                return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
            }
          Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
           
        }
        else{
            if($payment_form == 'wallet'){
                return Redirect::to(route('user.wallet'))->with('error', 'Transaction has been cancelled');
            } elseif($payment_form == 'tip'){
                return Redirect::to(route('user.orders'))->with('error', 'Transaction has been cancelled');
            } elseif($payment_form == 'subscription'){
                return Redirect::to(route('user.subscription.plans'))->with('error', 'Transaction has been cancelled');
            }
        }
    }
    function vnpay_respontAPP(Request $request){
       
      // pr($request->all());
        $inputData = array();
        $vnp_HashSecret = $this->vnp_HashSecret;
       
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

           
        $order_number = $inputData['vnp_TxnRef'];
        $meta_data = json_decode($inputData['vnp_OrderInfo']);
            
       
        $payment_form = $meta_data->payment_form;
        $returnUrl = url('payment/gateway/returnResponse');
        if($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00' ){
            
            $returnUrlParams = '?status=200&gateway=vnpay&action=' . $payment_form;
            
            return Redirect::to(url($returnUrl . $returnUrlParams));
        }
        else{
            $returnUrlParams = '?status=0&gateway=vnpay&action=' .$request->payment_form;
            
            return Redirect::to(url($returnUrl . $returnUrlParams));
        }
    }
    public function VnpayNotify(Request $request, $domain = '')
    {
        \Log::info("web hooke hit");
        try{
           
            $inputData = array();
            $vnp_HashSecret = $this->vnp_HashSecret;
            
            foreach ($request->all() as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
            $meta_data = json_decode($inputData['vnp_OrderInfo']);
        
     
            $payment_form = $meta_data->payment_form;
          
            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            $user_id =  $meta_data->user_id;
            $order_number = $inputData['vnp_TxnRef'];
 
            $amount = ($inputData['vnp_Amount'] / 100 );
            
            $transactionId = $inputData['vnp_TransactionNo'] ;
            if($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00'){
                if($payment_form == 'wallet'){
                    $request->request->add(['user_id' => $user_id, 'wallet_amount' => $amount,'payment_option_id'=>15  ,'transaction_id' => $transactionId]);
                    $walletController = new WalletController();
                    $walletController->creditAgentWallet($request);
                }
            }
        }
        catch(Exception $ex){
            \Log::info($ex->getMessage());
        }
        http_response_code(200);
    }
}
