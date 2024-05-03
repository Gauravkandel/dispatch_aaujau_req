<?php

namespace App\Jobs;

use App\Model\Roster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Config;
use Log;
class RosterDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id,$type)
    {
        $this->order_id      = $order_id;
        $this->type      = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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

            // config(["database.connections.mysql.database" => null]);

        //    Log::info($this->order_id);

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            if($this->type=='B'){
                DB::connection($schemaName)->table('rosters')->where('batch_no',$this->order_id)->delete();
            }else{
                DB::connection($schemaName)->table('rosters')->where('order_id',$this->order_id)->delete();
            }
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
    }
}
