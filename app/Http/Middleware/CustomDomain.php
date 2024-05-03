<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Model\{Client, ClientPreference};
use Config;
use Cache;
use Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client as TwilioC;
class CustomDomain
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
     // if(!Auth::user()){
      $domain = $request->getHost();
      $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $domain);
      $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $domain);
      $subDomain = explode('.', $domain);
     
    //   $existRedis = '';
    //  if(!$existRedis){
        
    //     $client = Client::select('*')
    //                 ->where(function($q) use($domain, $subDomain){
    //                           $q->where('custom_domain', $domain)
    //                             ->orWhere('sub_domain', $subDomain[0]);
    //                             //->orWhere('database_name', $subDomain[0]);
    //                 })
    //                 ->firstOrFail();
        
    
    //      $saveDataOnRedis = Cache::set('clientdetails',$client);
         
    //   }
    //   $callback = '';

    //   $redisData = $client;
    //   $dbname = DB::connection()->getDatabaseName();
    //  if($domain){
    //         if($domain != env('Main_Domain')){
    //               if($client && $dbname != 'db_'.$client->database_name){
    //               $database_name = 'db_'.$client->database_name;
    //               $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST','127.0.0.1');
    //               $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT','3306');
    //               $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME','royodelivery_db');
    //               $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD','');
    //               $default = [
    //                   'driver' => env('DB_CONNECTION','mysql'),
    //                   'host' => $database_host,
    //                   'port' => $database_port,
    //                   'database' => $database_name,
    //                   'username' => $database_username,
    //                   'password' => $database_password,
    //                   'charset' => 'utf8mb4',
    //                   'collation' => 'utf8mb4_unicode_ci',
    //                   'prefix' => '',
    //                   'prefix_indexes' => true,
    //                   'strict' => false,
    //                   'engine' => null,
    //                   'options' => [],
    //               ];
                  
    //              // Config::set("database.connections.mysql", $default);
    //               \Config::set('database.connections.mysql.database',$database_name);      
    //               DB::purge('mysql');   
    //             //  Config::set("client_id",$client);
    //             //  Config::set("client_connected",true);
    //             //  Config::set("client_data",$client);
    //             //  DB::setDefaultConnection($database_name);
    //            //   DB::purge('mysql');
    //               $dbname = DB::connection()->getDatabaseName(); 
                
                  
    //             }
    //           }
            
    //     }
    // else{
    //       return view('pages/404');
    //     }
     
    //  // }
      return $next($request);
    }
}