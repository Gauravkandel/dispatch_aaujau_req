<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Client;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class UpdatePassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $password;
    protected $client_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($password,$client_data)
    {
        $this->password    = $password;
        $this->client_data = $client_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        //$client = Client::where('id', $this->client_id)->first(['name', 'email', 'password', 'phone_number', 'password', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status'])->toarray();

        $schemaName = 'royodelivery_db' ?: config("database.connections.mysql.database");
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

        config(["database.connections.mysql.database" => null]);



        Config::set("database.connections.$schemaName", $default);
        config(["database.connections.mysql.database" => $schemaName]);
        if($this->client_data != 'empty'){
            DB::connection($schemaName)->table('clients')->where('code',Auth::user()->code)->update(['name'=>$this->client_data['name'],'email'=>$this->client_data['email'],'phone_number'=>$this->client_data['phone_number'],'company_name'=>$this->client_data['company_name'],'company_address'=>$this->client_data['company_address'],'logo'=>$this->client_data['logo'],'dark_logo'=>$this->client_data['dark_logo'],'country_id'=>$this->client_data['country_id'],'timezone'=>$this->client_data['timezone']]);
        }else{
            DB::connection($schemaName)->table('clients')->where('code',Auth::user()->code)->update(['password'=>$this->password['password'],'confirm_password'=>$this->password['confirm_password']]);
        }
        
        DB::disconnect($schemaName);
    }
}
