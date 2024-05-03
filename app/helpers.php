<?php
use Carbon\Carbon;
use App\Model\ClientPreference;
use App\Model\Client as ClientData;
use App\Model\Countries;
use App\Model\PaymentOption;

function pr($var) {
  	echo '<pre>';
	print_r($var);
  	echo '</pre>';
    exit();
}
function http_check($url) {
    $return = $url;
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $return = 'http://' . $url;
    }
    return $return;
}

function getMonthNumber($month_name){
    if($month_name == 'January'){
        return 1;
    }else if($month_name == 'February'){
        return 2;
    }else if($month_name=='March'){
        return 3;
    }else if($month_name=='April'){
        return 4;
    }else if($month_name=='May'){
        return 5;
    }else if($month_name=='June'){
        return 6;
    }else if($month_name=='July'){
        return 7;
    }else if($month_name=='August'){
        return 8;
    }else if($month_name=='September'){
        return 9;
    }else if($month_name=='October'){
        return 10;
    }else if($month_name=='November'){
        return 11;
    }else if($month_name=='December'){
        return 12;
    }
}
function generateOrderNo($length = 8){
    $number = '';
    do {
        for ($i=$length; $i--; $i>0) {
            $number .= mt_rand(0,9);
        }
    } while (!empty(\DB::table('orders')->where('order_number', $number)->first(['order_number'])) );
    return $number;
}
function generateUniqueTransactionID(){
    $ref = 'txn_'.uniqid(time());
    return $ref;
}
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s'){
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}
function getClientPreferenceDetail()
{
    $client_preference_detail = ClientPreference::first();
    return $client_preference_detail;
}
function getClientDetail()
{
    $clientData = ClientData::first();
    return $clientData;
}
function getRazorPayApiKey()
{
    $razorpay_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'razorpay')->where('status', 1)->first();
    $api_key_razorpay = "";
    if($razorpay_creds)
    {
        $creds_arr_razorpay = json_decode($razorpay_creds->credentials);
        $api_key_razorpay = (isset($creds_arr_razorpay->api_key)) ? $creds_arr_razorpay->api_key : '';
    }
    return $api_key_razorpay;
}

function dateTimeInUserTimeZone($date, $timezone, $showDate=true, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $date_format = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    if($date_format == 'DD/MM/YYYY'){
        $date_format = 'DD-MM-YYYY';
    }
    $time_format = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    $dateFormat = '';
    if($showDate){
        $dateFormat = $date_format;
    }
    if($showTime){
        if($showSeconds){
            $secondsKey = ':ss';
        }
        if($time_format == '12'){
            $timeFormat = ' hh:mm'.$secondsKey.' A';
        }else{
            $timeFormat = ' HH:mm'.$secondsKey;
        }
    }

    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
}

function helper_number_formet($number){
    return number_format($number,2);
}

function getCountryCode($dial_code=''){
    if($dial_code==''):
        $clientData = ClientData::select('country_id')->first();
        $getAdminCurrentCountry = Countries::where('id', '=', $clientData->country_id)->select('id', 'code')->first();
    else:
        $getAdminCurrentCountry = Countries::where('phonecode', '=', $dial_code)->select('id', 'code')->first();
    endif;

    if (!empty($getAdminCurrentCountry)) {
        $countryCode = $getAdminCurrentCountry->code;
    } else {
        $countryCode = '';
    }
    return $countryCode;
}

function getCountryPhoneCode(){
    $clientData = ClientData::select('country_id')->first();
    $getAdminCurrentCountry = Countries::where('id', '=', $clientData->country_id)->select('id', 'phonecode')->first();
    if (!empty($getAdminCurrentCountry)) {
        $countryCode = $getAdminCurrentCountry->phonecode;
    } else {
        $countryCode = '';
    }
    return $countryCode;
}

function getAgentNomenclature()
{
    $reference = ClientPreference::first();
    return (empty($reference->agent_name))?'Agent':$reference->agent_name;
}
