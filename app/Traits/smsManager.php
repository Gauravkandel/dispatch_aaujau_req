<?php
namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use Log;
use Unifonic;
trait smsManager{

  public function __construct()
  {
    //
  }


    public function mTalkz_sms($to,$message,$crendentials)
    {
        $api_url = "http://msg.mtalkz.com/V2/http-api.php";
        $to_number = substr($to, 1);
        $endpoint = $api_url.'?apikey='.$crendentials->api_key.'&senderid='.$crendentials->sender_id.'&number='.$to_number.'&message='.$message.'&format=json';
        $response=$this->getGuzzle($endpoint);
        return $response;
    }

    public function mazinhost($to,$message,$crendentials)
    {
        $curl = curl_init();

        $from = $crendentials->sender_id;
        $to = substr($to, 1);

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://mazinhost.com/smsv1/sms/api",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "action=send-sms&api_key=$crendentials->api_key&to=$to&from=$from&sms=$message",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

        // $api_url = " https://mazinhost.com/smsv1/sms/api";
        // $to_number = substr($to, 1);
        // $endpoint = $api_url.'?action=send-sms&api_key='.$crendentials->api_key.'&to='.$to_number.'&from='.$crendentials->sender_id.'&sms='.$message;
        // $response=$this->getGuzzle($endpoint);
        //return $endpoint;
    }

    private function postCurl($data,$token=null):object{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result);
    }
    public function unifonic($recipient,$message,$crendentials)
    { 
      
        //$crendentials = (object)$crendentials; 
        try{
            $crendential = [
                'app_id'=> $crendentials->unifonic_app_id,
                'account_email'=> $crendentials->unifonic_account_email,
                'account_password'=> $crendentials->unifonic_account_password
            ];
            config(['services.unifonic' => $crendential]);
            $to_number = substr($recipient, 1);
            $respont = Unifonic::send( $to_number, $message, $senderID = null);            
            //Log::info($respont);
            Log::info("unifonic sms respont");
            return 1;
        }catch(Exception $e) {
            return $e->getMessage();
        }

    }
    public function arkesel_sms($to,$message,$crendentials)
    {
        $to_number = substr($to, 1);
        $api_url = "https://sms.arkesel.com/sms/api?action=send-sms&";
        $endpoint = $api_url.'api_key='.$crendentials->api_key.'&to='.$to_number.'&from='.$crendentials->sender_id.'&sms='.urlencode($message);
        $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    private function getCurl($endpoint):object{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
        return json_decode($result);

        $curl = curl_init();

    }
    public function getGuzzle($endpoint)
    {
       // pr($endpoint);
        try{
            $client = new \GuzzleHttp\Client();
            $res = $client->get($endpoint);
            return $res->getStatusCode(); // 200
        }catch(Exception $e) {
            dd($e);
        }
    }

    public function aakash_sms($to,$message,$crendentials){
        $auth_token = $crendentials->api_key;
        $curl       = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sms.aakashsms.com/sms/v3/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('auth_token' => $auth_token,'to' =>$to,'text' => $message),
            CURLOPT_HTTPHEADER => array(
                'Cookie: XSRF-TOKEN=eyJpdiI6IlJKbmdOc0Y4TmxrS1ZnK1pcL0VzeHJnPT0iLCJ2YWx1ZSI6InltZFVjODFDelFIQjlrNDI3VW9PMHl6dzJCYWt2ckdzV1wvTGczWTFrak04TENFK20wYk1cL1h2TDl0RktJWTdyOWZKRDRaVHZOMFlvd3Q1dDlFTUpQRnc9PSIsIm1hYyI6IjZlOTk3YzMxMTBkOTY2MWEzNmIzYTg2YzhmN2M3ZDc3NjhiZTkzYjNmMTkwYjdhNzdjOThkYTk2MGZlMmQyZjYifQ%3D%3D; laravel_session=eyJpdiI6IktHV0x4UFV3cEMwM1cyZlZvU2o0MVE9PSIsInZhbHVlIjoiU3oxRjNSODBhQ1wvMmZyWEdHM2Vxd0duQVZQRGE3K1NPa0tCUExEaEFNK1NaY3RoWkxmUVNyK2NGa1BJU2hKRDE1c0ZrOUVINldMN2NIY2tpdjF5ekVBPT0iLCJtYWMiOiI2NDMzMzY4MmU5NzJjODU4ZGI0YTcyOTQ5ODAxY2UwYjYwNzJmZWJmN2RlOWU2YWM4MDcyMzcyMDk1NTIzZThiIn0%3D'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

}
