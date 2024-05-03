<?php

namespace App\Listeners;

use App\Events\PushNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
Use Log;
use Carbon\Carbon;
use App\Model\Roster;
use App\Model\Client;
use Config;
use Illuminate\Support\Facades\DB;
use Exception;
use Kawankoding\Fcm\Fcm;

class SendPushNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PushNotification  $event
     * @return void
     */
    public function handle(PushNotification $event)
    {
       //Log::info('handle listener working inder');

        $date =  Carbon::now()->toDateTimeString();

        try {

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

                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);

                $this->getData();


                DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }


    }

    public function getData()
    {
        //Log::info('rostersIDs lisner');
        //Log::info('listener getdata working inder');

        $schemaName       = 'royodelivery_db';
        $date             =  Carbon::now()->toDateTimeString();
        $get              =  DB::connection($schemaName)->table('rosters')
                                        ->where(function ($query) use ( $date) {
                                            $query->where('notification_time', '<=', $date)
                                                ->orWhere('notification_befor_time', '<=', $date);
                                        })->where('status',0)
                                    ->leftJoin('roster_details', 'rosters.detail_id', '=', 'roster_details.unique_id')
                                    ->select('rosters.*', 'roster_details.customer_name', 'roster_details.customer_phone_number',
        'roster_details.short_name','roster_details.address','roster_details.lat','roster_details.long','roster_details.task_count')->get();
        $getids           = $get->pluck('id');
        //$qr           = $get->toSql();
       // Log::info($qr);
        //Log::info($getids);
        DB::connection($schemaName)->table('rosters')->where('status',10)->delete();
        if(count($get) > 0){
        //Log::info('getdata count inder'.count($get));
            DB::connection($schemaName)->table('rosters')->whereIn('id',$getids)->delete();
            // DB::connection($schemaName)->table('rosters')->whereIn('id',$newget)->update(['status'=>1]);

           // Log::info('getdata count inder='.count($get));
            $this->sendnotification($get);
        }else{
            Log::info('Empty Roaster lisner');
            $this->extraTime($schemaName);


        }

        return;



    }

    public function sendnotification($recipients)
    {
        //Log::info('sendnotification listener came');
         
    try {

        $array = json_decode(json_encode($recipients), true);
      //  Log::info(json_encode($recipients));


        foreach($array as $item){

            if(isset($item['device_token']) && !empty($item['device_token'])){
                //Log::info('Fcm Response 11');

                $item['title']     = 'Pickup Request';
                $item['body']      = 'Check All Details For This Request In App';
                $new = [];
               // Log::info($item);
               // Log::info('token=');

               $item['notificationType'] = $item['type'];
               unset($item['type']); // done by Preet due to notification title is displaying like AR in iOS 

                array_push($new,$item['device_token']);
               // Log::info($new);

                $clientRecord = Client::where('code', $item['client_code'])->first();
                $this->seperate_connection('db_'.$clientRecord->database_name);
                $client_preferences = DB::connection('db_'.$clientRecord->database_name)->table('client_preferences')->where('client_id', $item['client_code'])->first();
                   // Log::info('Fcm Response');

                if(isset($new)){
                    try{
                        $fcm_server_key = !empty($client_preferences->fcm_server_key)? $client_preferences->fcm_server_key : 'null';
                        //Log::info($fcm_server_key);

                        $fcmObj = new Fcm($fcm_server_key);
                        $fcm_store = $fcmObj->to($new) // $recipients must an array
                                        ->priority('high')
                                        ->timeToLive(0)
                                        ->data($item)
                                        ->notification([
                                            'title'              => 'Pickup Request',
                                            'body'               => 'Check All Details For This Request In App',
                                            'sound'              => 'notification.mp3',
                                            'android_channel_id' => 'Royo-Delivery',
                                            'soundPlay'          => true,
                                            'show_in_foreground' => true,
                                        ])
                                        ->send();

                                           // Log::info('Fcm Response in');
                                            //Log::info($fcm_store);
                    }
                    catch(Exception $e){
                        Log::info($e->getMessage());
                    }

                }
            }


        }

        sleep(5);
        $this->getData();
        } catch (Exception $ex) {
            Log::info($ex->getMessage());

        }

    }

    public function extraTime($schemaName)
    {
        Log::info('extraTime');
        //sleep(30); ->addSeconds(45)
        $date             =  Carbon::now()->toDateTimeString();
        Log::info($date);
        $check = DB::connection($schemaName)->table('rosters')
                        ->where(function ($query) use ( $date) {
                            $query->where('notification_time', '<=', $date)
                                ->orWhere('notification_befor_time', '<=', $date);
                        })
                    ->get();
         Log::info(DB::connection($schemaName)->table('rosters')
         ->get()->pluck('id'));
        if(count($check) > 0){
            sleep(15);
            $this->getData();
        }else{
            return;
        }
    }

    public function seperate_connection($schemaName){
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

        Config::set("database.connections.$schemaName", $default);
    }

}
