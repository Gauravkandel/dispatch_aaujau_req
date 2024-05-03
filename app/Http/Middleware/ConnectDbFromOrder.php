<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
class ConnectDbFromOrder
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
       
        config(['auth.guards.api.provider' => 'agents']);
        $database_name = $database = 'royodelivery_db';
        $header = $request->header();        
        if (array_key_exists("shortcode", $header)){
            $shortcode =  $header['shortcode'][0];
        }

        
        
        if (array_key_exists("personaltoken", $header)){
            $personaltoken =  $header['personaltoken'][0];
        }
        //$client = Cache::get($database);
        $client = Client::where('is_deleted', 0)->where('code', $shortcode)->first(['id', 'name', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked']);
        
        if (!$client) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid short code. Please enter a valid short code.']);
        }
        if ($client->is_blocked == 1) {
            return response()->json([
                'status' => 400,
                'message' => 'Company has been blocked. Please contact administration.']);
        }      
        if (isset($client)) {
            $database_name =  'db_'.$client['database_name'];
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
          //  Config::set("client_data", $client);
            DB::setDefaultConnection($database_name);
            DB::purge($database_name);
            //DB::reconnect($database_name);
            if(isset($personaltoken) && !empty($personaltoken)){
                $client_toke = Client::where('is_deleted', 0)->where('code',$shortcode)
                ->whereHas('getPreference',function($q)use($personaltoken){
                    $q->where('personal_access_token_v1',$personaltoken);
                })->first(['id', 'name', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked']);
            }else{
                $client_toke = 1;
            }
           
            if($client_toke)
            return $next($request);
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid Dispatcher API Values']);
            }
        }
        abort(404);
    }
}


