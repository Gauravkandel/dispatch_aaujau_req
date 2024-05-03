<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\ClientNotificationController;
use Log;
use App\Model\Roster;
use Carbon\Carbon;
use App\Jobs\SendPushNotifications;
use App\Model\Client;
use Config;
use Illuminate\Support\Facades\DB;

class SendPushNotification extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
                        
        
        
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clients = Client::where('status', 1)->get('database_name');
        foreach($clients as $client){
           
            dispatch(new SendPushNotifications($client->database_name));
        }
        
    }

    
}
