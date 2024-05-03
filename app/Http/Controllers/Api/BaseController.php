<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client as TwilioClient;
use App\Traits\smsManager;
use App\Model\{Agent, PaymentOption, Client, ClientPreference, AgentSavedPaymentMethod};

class BaseController extends Controller
{
    use smsManager;
    use ApiResponser;

	protected function sendSms($recipients, $message)
	{
	    $sid = getenv("TWILIO_SID");
	    $token = getenv("TWILIO_AUTH_TOKEN");
	    $twilio_number = getenv("TWILIO_NUMBER");
	    $client = new TwilioClient($account_sid, $auth_token);
	    $client->messages->create('+91'.$recipients,
	            ['from' => $twilio_number, 'body' => $message]
            );
	}

	public function paginate($items, $perPage = 15, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage),
		$items->count(), $perPage, $page, $options);
	}

    protected function sendSms2($to, $body){
        try{
            $client_preference =  getClientPreferenceDetail();

            if($client_preference->sms_provider == 1)
            {
                $credentials = json_decode($client_preference->sms_credentials);
                $sms_key = (isset($credentials->sms_key)) ? $credentials->sms_key : $client_preference->sms_provider_key_1;
                $sms_secret = (isset($credentials->sms_secret)) ? $credentials->sms_secret : $client_preference->sms_provider_key_2;
                $sms_from  = (isset($credentials->sms_from)) ? $credentials->sms_from : $client_preference->sms_provider_number;

                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }elseif($client_preference->sms_provider == 2) //for mtalkz gateway
            {
                $credentials = json_decode($client_preference->sms_credentials);
                $send = $this->mTalkz_sms($to,$body,$credentials);
            }elseif($client_preference->sms_provider == 3) //for mazinhost gateway
            {
                $credentials = json_decode($client_preference->sms_credentials);
                $send = $this->mazinhost_sms($to,$body,$credentials);
            }elseif($client_preference->sms_provider == 4) //for unifonic gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->unifonic($to,$body,$crendentials);
            }
            elseif($client_preference->sms_provider == 5) //for arkesel_sms gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->arkesel_sms($to,$body,$crendentials);
                if( isset($send->code) && $send->code != 'ok'){
                    return $this->error($send->message, 404);
                }

            }
            elseif($client_preference->sms_provider == 6) //for aakashsms gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->aakash_sms($to,$body,$crendentials);
                \Log::info("Error ".$send->error);
                \Log::info("Mobile No.".$to);
                if($send->error){
                    return $this->error($send->message, 404);
                }

            }
            else{
                $credentials = json_decode($client_preference->sms_credentials);
                $sms_key = (isset($credentials->sms_key)) ? $credentials->sms_key : $client_preference->sms_provider_key_1;
                $sms_secret = (isset($credentials->sms_secret)) ? $credentials->sms_secret : $client_preference->sms_provider_key_2;
                $sms_from  = (isset($credentials->sms_from)) ? $credentials->sms_from : $client_preference->sms_provider_number;
                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }
        }
        catch(\Exception $e){
            \Log::info($e->getMessage());
            // return $this->error(__('Provider service is not configured. Please contact administration.'), 404);
            return $this->error($e->getMessage(), $e->getCode());
        }
        return $this->success([], __('An otp has been sent to your phone. Please check.'), 200);
	}

    /* Save user payment method */
    public function saveUserPaymentMethod($request)
    {
        $payment_method = new AgentSavedPaymentMethod;
        $payment_method->agent_id = Auth::user()->id;
        $payment_method->payment_option_id = $request->payment_option_id;
        $payment_method->card_last_four_digit = $request->card_last_four_digit;
        $payment_method->card_expiry_month = $request->card_expiry_month;
        $payment_method->card_expiry_year = $request->card_expiry_year;
        $payment_method->customerReference = ($request->has('customerReference')) ? $request->customerReference : NULL;
        $payment_method->cardReference = ($request->has('cardReference')) ? $request->cardReference : NULL;
        $payment_method->save();
    }

    /* Get Saved user payment method */
    public function getSavedUserPaymentMethod($request)
    {
        $saved_payment_method = AgentSavedPaymentMethod::where('agent_id', Auth::user()->id)
                        ->where('payment_option_id', $request->payment_option_id)->first();
        return $saved_payment_method;
    }

}
