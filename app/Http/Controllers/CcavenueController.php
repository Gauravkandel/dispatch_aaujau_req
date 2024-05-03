<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\{BaseController, WalletController};
use App\Model\{Agent, Payment, PaymentOption, PayoutOption, Client, ClientPreference, AgentConnectedAccount};
use Log;

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
    

    public function viewForm(Request $request)
    {
        $this->configuration();
            if(isset($request->tk) && !empty($request->tk)){
            $user = Agent::where('access_token', $request->tk)->first();
            Auth::login($user);
         }

        $encrypted_data = $request->encData;
        $access_code = $request->access_code;
        $url = $request->url;
        return view('ccavenue_view', compact('encrypted_data','access_code','url'));
    }
 
    public function successForm(Request $request)
    {
    $this->configuration();
     $encResponse=$request->encResp;			//This is the response sent by the CCAvenue Server
     $rcvdString=$this->decrypt($encResponse,$this->access_key);		//Crypto Decryption used as per the specified working key.
       $order_status="";
       $decryptValues=explode('&', $rcvdString);
 
       $dataSize=sizeof($decryptValues);
     $dataArray = array();
     for($i = 0; $i < $dataSize; $i++) 
     {
       $information=explode('=',$decryptValues[$i]);
       $request->request->add([$information[0] => $information[1]]);
     }
     $request = (object)$request;

     if(isset($request->merchant_param5) && !empty($request->merchant_param5)){
         $user = Agent::where('access_token',$request->merchant_param5)->first();
         Auth::login($user);
      }


        $returnUrl = url('payment/gateway/returnResponse');
        if(isset($request->order_status) && $request->order_status == 'Success')
        {

            if($request->merchant_param2 == 'wallet'){
                $request->request->add(['user_id' => auth()->id(), 'wallet_amount' => $request->amount,'payment_option_id'=>16  ,'transaction_id' => $request->tracking_id]);
                $walletController = new WalletController();
                $walletController->creditAgentWallet($request);
            }

            
            $returnUrlParams = '?status=200&gateway=ccavenue&action=' . $request->merchant_param2;
            
            return Redirect::to(url($returnUrl . $returnUrlParams));
        }
        else{
            $returnUrlParams = '?status=0&gateway=ccavenue&action=' .$request->merchant_param2;
            
            return Redirect::to(url($returnUrl . $returnUrlParams));
        }
 
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
 