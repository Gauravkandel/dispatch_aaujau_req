<?php

namespace App\Http\Middleware;

use App\Model\Client;
use App\Model\ClientPreference;
use Closure;
use Config;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client as TwilioC;

class DatabaseDynamic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        if(Auth::check()){
         
          $client = Auth::user();
           if($client){
              // $database_name = 'db_'.$client->database_name;
              // $default = [
              //     'driver' => env('DB_CONNECTION','mysql'),
              //     'host' => env('DB_HOST'),
              //     'port' => env('DB_PORT'),
              //     'database' => $database_name,
              //     'username' => env('DB_USERNAME'),
              //     'password' => env('DB_PASSWORD'),
              //     'charset' => 'utf8mb4',
              //     'collation' => 'utf8mb4_unicode_ci',
              //     'prefix' => '',
              //     'prefix_indexes' => true,
              //     'strict' => false,
              //     'engine' => null
              // ];
              // Config::set("database.connections.$database_name", $default);
              // Config::set("client_id",$client);
              // Config::set("client_connected",true);
              // Config::set("client_data",$client);
              // DB::setDefaultConnection($database_name);
              // DB::purge($database_name);

              $clientPreference = ClientPreference::where('client_id',Auth::user()->code)->first();

              if(isset($clientPreference)){
                $agentTitle = empty($clientPreference->agent_name) ? 'Agent' : $clientPreference->agent_name;
                Session::put('agent_name', $agentTitle);
                Session::put('preferences', $clientPreference->toArray());

              }else{
                Session::put('agent_name', 'Agent');
                Session::put('preferences', '');
              }

             // dd($clientPreference->toArray());

              if($clientPreference){

                if(!empty($clientPreference->sms_provider_key_1) && !empty($clientPreference->sms_provider_key_2)){

                  $token = $clientPreference->sms_provider_key_1;
                  $sid = $clientPreference->sms_provider_key_2;
                  $twilio = new TwilioC($sid, $token);
                  try {
                    $account = $twilio->api->v2010->accounts($sid)->fetch();

                    Session::put('twilio_status', $account->status);

                  } catch (\Exception $e) {
                      Session::put('twilio_status', 'invalid_key');
                  }

                }else{
                  Session::put('twilio_status', 'null_key');
                }

                
              }

              
              //Session::put('testImage', url('profileImg'));
              
          }
      }
        return $next($request);
    }
}
