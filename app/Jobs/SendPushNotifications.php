<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Carbon\Carbon;
use App\Model\Roster;
use App\Model\Client;
use Config;
use Illuminate\Support\Facades\DB;

class SendPushNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client_db;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_db)
    {
        $this->client_db = $client_db;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
        //   print_r('ok_notification');
            $schemaName = 'royodelivery_db';
            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $schemaName,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];

            // config(["database.connections.mysql.database" => null]);



            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            Log::info('SendPushNotifications rosters insert');
            DB::connection($schemaName)->table('rosters')->insert($this->data);

            Log::info('App\Jobs\SendPushNotifications rosters insert');
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }

        $recipients = [];
        $date =  Carbon::now()->toDateTimeString();
        $database_name = 'db_' .$this->client_db;
        $default = [
            'driver'         => env('DB_CONNECTION', 'mysql'),
            'host'           => env('DB_HOST'),
            'port'           => env('DB_PORT'),
            'database'       => $database_name,
            'username'       => env('DB_USERNAME'),
            'password'       => env('DB_PASSWORD'),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => false,
            'engine'         => null
        ];



        Config::set("database.connections.$database_name", $default);
        $counter = 12;
        while ($counter) {
            $counter--;
            $get =  DB::connection($database_name)->table('rosters')->where('notification_time', '<=', $date)
            ->leftJoin('agents', 'rosters.driver_id', '=', 'agents.id')->select('agents.device_token','agents.device_type')->get();

            foreach($get as $item){
                if(isset($item->device_token))
                array_push($recipients,$item->device_token);
            }
            Log::info('App\Jobs\SendPushNotifications');
            $this->sendnotification($recipients);
            sleep(5);
        }

    }

    public function sendnotification($recipients)
    {

        //Log::info($recipients);
        if(isset($recipients)){
            fcm()
            ->to($this->recipients) // $recipients must an array
            ->priority('high')
            ->timeToLive(0)
            ->data([
                'title' => 'Test FCM',
                'body' => 'This is a test of FCM',
            ])
            ->notification([
                'title' => 'Test FCM',
                'body' => 'This is a test of FCM',
            ])
            ->send();
        }

        //Log::info('sendinglog');

    }

    public function failed(\Throwable $exception)
    {
        // Log failure
        Log::info($exception);
    }
}
