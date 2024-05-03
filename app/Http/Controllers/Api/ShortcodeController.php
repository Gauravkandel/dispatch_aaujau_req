<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Config;
use Validation;
use DB;

class ShortcodeController extends BaseController
{
    /**
     * Get Company ShortCode
     *
     */
    public function validateCompany(Request $request)
    {
        $client = Client::where('is_deleted', 0)->where('code', $request->shortCode)->select('id','country_id', 'name', 'phone_number', 'email', 'database_name', 'timezone', 'custom_domain', 'database_host', 'database_port', 'database_username', 'database_password', 'logo', 'dark_logo', 'company_name', 'company_address', 'is_blocked', 'socket_url')->with('getCountrySet')->first();

      
        if (!$client) {
            return response()->json([
                'error' => 'Company not found',
                'message' => 'Invalid short code. Please enter a valid short code.'], 404);
        }
        if ($client->is_blocked == 1) {
            return response()->json([
                'error' => 'Blocked Company',
                'message' => 'Company has been blocked. Please contact administration.'], 404);
        }
       
        $img = env('APP_URL').'/assets/images/default_image.png';

        if (file_exists(public_path().'/assets/images/'.$client->logo)) {
            $img = public_path().'/assets/images/'.$client->logo;
        }
        if ($client->logo) {
            $client->logo = \Storage::disk("s3")->url($client->logo);
        }

        if($client->dark_logo){
            $client->dark_logo = \Storage::disk("s3")->url($client->dark_logo);
        }
        


        if (!empty($client)) {
            $database_name =  'db_'.$client->database_name;
            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST','127.0.0.1');
            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT','3306');
            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME','cbladmin');
            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD','');
            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
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
            Config::set("client_connected", true);
            DB::setDefaultConnection($database_name);
            DB::purge($database_name);
           
            $client_db_data = Client::where('is_deleted', 0)->where('code',$request->shortCode)->select('id', 'code')->first();
            if(!empty($client_db_data)){
                $client->client_db_id = $client_db_data->id;
                $client->client_db_code = $client_db_data->code;
            }
        }
        unset($client->database_host);
        unset($client->database_port);
        unset($client->database_username);
        unset($client->database_password);
        return response()->json([
            'data' => $client,
            'status' => 200,
            'message' => __('success')
        ]);
    }
}
