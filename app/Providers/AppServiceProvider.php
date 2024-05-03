<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\View;
use App\Model\Team;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Model\{Client, ClientPreference, PaymentOption};
use Illuminate\Support\Facades\Storage;
use Closure;
use Session;

use Auth, Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request = null)
    {
        if ($request !== null) {
            $this->connectDynamicDb($request);
        }
        if (config('app.env') != 'local') {
            URL::forceScheme('https');
        }
        //
        $favicon_url = asset('assets/images/favicon.ico');
        $clientDetails = Cache::get('clientdetails');
        if (!empty($clientDetails) && !empty($clientDetails->code)) {
            if (Cache::hasColumn('client_id', 'client_preferences')) {
                $preference  = ClientPreference::where('client_id', $clientDetails->code)->first();
                if (!empty($preference->fcm_server_key)) {
                    config(['laravel-fcm.server_key' => $preference->fcm_server_key ?? ""]);
                }
            }
        }
        $preference  = ClientPreference::where('client_id', ($clientDetails->code ?? ''))->first();
        if ($preference) {
            $favicon_url =  isset($preference->favicon) ? Storage::disk('s3')->url($preference->favicon) : '';
        }

        Builder::defaultStringLength(191);

        View::composer('modals.add-agent', function ($view) {
            $teams = Team::select('id', 'name')->get();
            $view->with(["teams" => $teams]);
        });

        View::composer('modals.add-customer', function ($view) {
            $tags = Tag::select('id', 'name')->get();
            $view->with(["tags" => $tags]);
        });
        $payment_codes = ['khalti'];
        $khalti_api_key = '';
        $payment_options = PaymentOption::select('code', 'credentials')->whereIn('code', $payment_codes)->where('status', 1)->get();
        if ($payment_options) {
            foreach ($payment_options as $option) {
                $creds = json_decode($option->credentials);
                if ($option->code == 'khalti') {
                    $khalti_api_key = (isset($creds->api_key) && (!empty($creds->api_key))) ? $creds->api_key : '';
                }
            }
        }
        view()->share('khalti_api_key', $khalti_api_key);
        view()->share('favicon', $favicon_url);
    }


    public function connectDynamicDb($request)
    {

        if (strpos(URL::current(), '/api/') !== false) {
        } else {
            $domain = $request->getHost();
            $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $domain);
            $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $domain);
            $subDomain = explode('.', $domain);
            if ($domain != env('Main_Domain')) {
                $existRedis = '';
                if (!$existRedis) {
                    $client = Client::select('*')
                        ->where(function ($q) use ($domain, $subDomain) {
                            $q->where('custom_domain', $domain)
                                ->orWhere('sub_domain', $subDomain[0]);
                        })->where('is_deleted', 0)->where('is_blocked', 0)
                        ->first();
                }
                $callback = '';
                $redisData = $client;
                $dbname = DB::connection()->getDatabaseName();

                if ($domain) {
                    if ($domain != env('Main_Domain')) {
                        if ($client && $dbname != 'db_' . $client->database_name) {
                            $saveDataOnRedis = Cache::set('clientdetails', $client);
                            $database_name = 'db_' . $client->database_name;
                            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
                            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
                            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'royodeliver');
                            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');
                            $default = [
                                'driver' => env('DB_CONNECTION', 'mysql'),
                                'host' => $database_host,
                                'port' => $database_port,
                                'database' => $database_name,
                                'username' => $database_username,
                                'password' => $database_password,
                                'charset' => 'utf8mb4',
                                'collation' => 'utf8mb4_unicode_ci',
                                'prefix' => '',
                                'prefix_indexes' => true,
                                'strict' => false,
                                'engine' => null
                            ];
                            Config::set('database.connections.mysql', $default);
                            //\Config::set('database.connections.mysql.database',$database_name);
                            DB::purge('mysql');
                            $dbname = DB::connection()->getDatabaseName();
                        }
                    }
                }
            }
        }
    }
}
