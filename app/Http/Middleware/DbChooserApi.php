<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
class DbChooserApi
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
        //$client = Cache::get($database);

        if (array_key_exists("client", $header)){
            $database_name =  'db_'.$header['client'][0];
        }

       
     

        if (isset($database_name)) {
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

            return $next($request);
        }
        abort(404);
    }
}


