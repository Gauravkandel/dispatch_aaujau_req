<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Config;

class NewChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newchanges:run {database}';

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
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('database');
        $get_user = Cache::get($username);


        try {

            $schemaName = 'db_' . $get_user['database_name'] ?: config("database.connections.mysql.database");
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
            \DB::connection($schemaName)->table('languages')->insert(['name' => 'anil', 'sort_code' => 'an']);
            \DB::disconnect($schemaName);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
