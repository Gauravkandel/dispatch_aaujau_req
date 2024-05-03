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
use App\Model\{Agent, User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax};
use Illuminate\Support\Facades\Auth as FacadesAuth;

class CcavenueController extends BaseController{
    use ApiResponser;
 
    private $access_key;
    private $merchant_id;
    private $url;
    private $access_code;

    public function configuration()
    {
       $payOpt = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'ccavenue')->where('status', 1)->first();
       $json = json_decode($payOpt->credentials);
       $this->access_key = $json->enc_key;
       $this->access_code = $json->access_code;
       $this->merchant_id = $json->merchant_id;
     if($payOpt->test_mode =='1')
     {
         $this->url = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction';
     }else{
         $this->url = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction';
     }
 
    }
 
public function paywebView(Request $request)
    {
        $this->configuration();
        $token =  auth()->user()->access_token;
      
     $merchant_data='';
     $number = $request->order_number ?? time(); // order no
     $orderType = $request->order_type ?? 'billpayment' ;
     $working_key=$this->access_key;//Shared by CCAVENUES
     $access_code=$this->access_code;//Shared by CCAVENUES
     $url=$this->url;//Shared by CCAVENUES
     $user = auth()->user();
     
     $merchant_data = 'merchant_id='.$this->merchant_id.'&order_id='.$number.'&amount='.$request->amount.'&currency=AED&redirect_url='.url($request->serverUrl.'payment/ccavenue/success').'&cancel_url='.url($request->serverUrl.'payment/ccavenue/success').'&language=EN&billing_name='.$user->name.'&billing_address=&billing_city=&billing_state=&billing_zip=&billing_country=&billing_tel='.$user->phone_number.'&billing_email=&delivery_name='.$user->name.'&delivery_address=&delivery_city=&delivery_state=&delivery_zip=&delivery_country=&delivery_tel='.$user->phone_number.'&merchant_param1='.$number.'&merchant_param2=wallet&merchant_param3=mob&merchant_param4=&merchant_param5='.$token.'&promo_code=&customer_identifier=&';
     //return $merchant_data;die;
     $encrypted_data=$this->encrypt($merchant_data,$working_key); // Method for encrypting the data.
     $webUrl = url($request->serverUrl.'ccavenue/pay?encData='.$encrypted_data.'&access_code='.$access_code.'&url='.$url.'&tk='.$token);
     return $this->success($webUrl, __('Success'), 201);

     //return view('ccavenue_view', compact('encrypted_data','access_code','url'));
    }

 
 
 
  //*********** Function *********************
 
 
    function encrypt($plainText,$key)
    {
        $key = $this->hextobin(md5($key));
 
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
 
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }
 
    function decrypt($encryptedText,$key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }
    //*********** Padding Function *********************
 
     function pkcs5_pad ($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }
 
    //********** Hexadecimal to Binary function for php 4.0 version ********
 
    function hextobin($hexString) 
        { 
            $length = strlen($hexString); 
            $binString="";   
            $count=0; 
            while($count<$length) 
            {       
                $subString =substr($hexString,$count,2);           
                $packedString = pack("H*",$subString); 
                if ($count==0)
            {
                $binString=$packedString;
            } 
                
            else 
            {
                $binString.=$packedString;
            } 
                
            $count+=2; 
            } 
              return $binString; 
          }
 
 
 }