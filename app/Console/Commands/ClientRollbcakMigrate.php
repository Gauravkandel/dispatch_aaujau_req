<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Artisan;
use Config;
use App\Model\Client;
use Exception;

use Illuminate\Console\Command;

class ClientRollbcakMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migration:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For RollBack Last Migration';

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
     * @return mixed
     */
    public function handle()
    {

        $clients = Client::where('status', 1)->get();
        //$clients = Client::all();
        foreach ($clients as $key => $client) {


            // \DB::table('clients')
            //     ->where('id', $client->id)
            //     ->update(['status' => 1]);

            $database_name = 'db_' . $client->database_name;
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
            Artisan::call('migrate:rollback', ['--database' => $database_name]);
            // Artisan::call('db:seed', ['--database' => $database_name]);
            \DB::disconnect($database_name);
        }
    }
}
