<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Model\Client;
use App\Model\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validation;
use DB;
use Config;

class ImportThirdPartyUserController extends BaseController
{
    public function importCustomer(Request $request){
        try{
            $client = Client::where('is_deleted', 0)->where('code', $request->shortCode)->select('id','country_id', 'name', 'phone_number', 'email', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked')->with('getCountrySet')->first();
            if(!empty($client)){
                $database_name = 'db_'.$client->database_name;
                $default = [
                    'driver' => env('DB_CONNECTION','mysql'),
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'database' => $database_name,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => false,
                    'engine' => null
                ];
                Config::set("database.connections.$database_name", $default);
                Config::set("client_id",$client);
                Config::set("client_connected",true);
                Config::set("client_data",$client);
                DB::setDefaultConnection($database_name);
                DB::purge($database_name);
    
                $authentication = base64_encode("home_collection_api:9fbc69de210038bc7ca57d6cd36628141caf6e56");
                $ch = curl_init('https://capitaldx.limsabc.com/apiws.php?page=apiws_get&model=patient_Registration');
                $options = array(
                        CURLOPT_RETURNTRANSFER => true,         // return web page
                        CURLOPT_HEADER         => false,        // don't return headers
                        CURLOPT_FOLLOWLOCATION => false,         // follow redirects
                    // CURLOPT_ENCODING       => "utf-8",           // handle all encodings
                        CURLOPT_AUTOREFERER    => true,         // set referer on redirect
                        CURLOPT_CONNECTTIMEOUT => 20,          // timeout on connect
                        CURLOPT_TIMEOUT        => 20,          // timeout on response
                        CURLOPT_POST            => 1,            // i am sending post data
                        CURLOPT_POSTFIELDS     => 'fields:["id","name","first_name","last_name","date_of_birth","phone","email","address"]
                        filter:[["client_site_id","=","12"],["collection_scheduled","<>","1"]]
                        order:["id","desc"]
                        limit:[5000,0]',    // this are my post vars
                        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
                        CURLOPT_SSL_VERIFYPEER => false,        //
                        CURLOPT_VERBOSE        => 1,
                        CURLOPT_HTTPHEADER     => array(
                            "Authorization: Basic $authentication",
                            "Content-Type: application/json"
                        )
                );
    
                curl_setopt_array($ch,$options);
                $data = curl_exec($ch);
    
                $insertData = json_decode($data);
                // $customer = Customer::get();
                // print_r($customer);die;
                $data = [];
                foreach($insertData->data as $dat){
                    $customer = Customer::where('email', strtolower(trim($dat->email)))->get()->first();
                    $phoneNumber = str_replace('-', '', str_replace('(','', str_replace(') ','', $dat->phone)));
                    if(empty($customer) && !empty($dat->email) && !empty($phoneNumber)){
                        $insertValue = [
                            'name' => $dat->first_name.' '.$dat->last_name,
                            'email' => strtolower(trim($dat->email)),
                            'phone_number' => $phoneNumber,
                        ];
                        Customer::create($insertValue);
                    }
                }
    
                return response()->json([
                    'status' => 200,
                    'message' => __('success')
                ], 200);
            }
        }catch(Exception $ex){
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }
}
