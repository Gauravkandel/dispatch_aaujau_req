<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Log;
use App\Model\{ChatSocket, Client, Agent, DriverGeo,Order};
use Illuminate\Support\Facades\Config;


trait GlobalFunction{
    

   
    public static function socketDropDown()
    {
        $chatSocket= ChatSocket::where('status', 1)->get();
        return $chatSocket;
    }

    public static function checkDbStat($id)
    {
        try {
            $client = Client::find($id);
        
            $schemaName = 'db_' . $client->database_name;
            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');

            $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => $database_host,
            'port' => $database_port,
            'database' => $schemaName,
            'username' => $database_username,
            'password' => $database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];
    
            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            $return['schemaName'] =  $schemaName;
            $return['clientData'] =  $client;

            return $return;
        } catch (\Throwable $th) {
            $return['schemaName'] =  '';
            $return['clientData'] =  [];
            return $return;
        }
    
    }

    public function getGeoBasedAgentsData($geo, $agent_tag = '', $date, $cash_at_hand)
    {
        try {

            $geoagents_ids =  DriverGeo::where('geo_id', $geo);

           

            if($agent_tag !='')
            {
                $geoagents_ids = $geoagents_ids->whereHas('agent.tags', function($q) use ($agent_tag){
                    $q->where('name', '=', $agent_tag);
                });
            }

            $geoagents_ids =  $geoagents_ids->pluck('driver_id');

            $geoagents = Agent::whereIn('id',  $geoagents_ids)->with(['logs','order'=> function ($f) use ($date) {
                $f->whereDate('order_time', $date)->with('task');
            }])->orderBy('id', 'DESC')->get()->where("agent_cash_at_hand", '<', $cash_at_hand);


            return $geoagents;

        } catch (\Throwable $th) {
            return [];
        }
    
    }
   
   
   

}