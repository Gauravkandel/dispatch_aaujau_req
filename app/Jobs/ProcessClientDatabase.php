<?php

namespace App\Jobs;

use App\Model\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;
Use Log;

class ProcessClientDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {      

        $client = Client::where('id', $this->client_id)->first(['name', 'email', 'password', 'phone_number', 'password', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'dark_logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code','sub_domain'])->toarray();
        
       
        $teams    = array(array('name' => 'Car Team','location_accuracy'=>1,'location_frequency'=>1,'client_id'=>$client['code']),
                        array('name' => 'Moto Team','location_accuracy'=>1,'location_frequency'=>1,'client_id'=>$client['code']));
        $geo      = array(array('name'=> 'Chandigarh','description'=> 'This is chandigarh geo','geo_array'=> '(30.722193060918734, 76.81803091122487),(30.714814305242182, 76.80755956723073),(30.71201023004263, 76.81219442440846),(30.694298398185953, 76.79880483700612),(30.6991694760599, 76.79434164120534),(30.696217337000455, 76.79039342953541),(30.695184066995065, 76.78112371517994),(30.697398203461013, 76.77957876278737),(30.68101239087761, 76.75400121762135),(30.693707947798174, 76.74421651913502),(30.686179388754244, 76.73305852963307),(30.698579055472567, 76.72293050839284),(30.701678723255046, 76.72876699520924),(30.71983192053557, 76.71348913266041),(30.723668744295058, 76.71932561947682),(30.735473398091386, 76.70919759823659),(30.752440054615263, 76.73752172543385),(30.747129074771323, 76.74352987362721),(30.757308195503647, 76.75863607479909),(30.76202858190823, 76.7564044768987),(30.768076238713753, 76.76859243466237),(30.758930854436844, 76.77597387387135),(30.767191239502747, 76.79159505917409),(30.763208642343383, 76.80429800106862),(30.75745571108224, 76.80944784237721),(30.752587577652235, 76.80464132382252),(30.73724397145609, 76.8168292815862),(30.726915168213008, 76.81408269955494)','zoom_level'=> 13,'client_id'=> $client['code']),
                       array('name'=> 'Mohali','description'=> 'This is Mohali geo','geo_array'=> '(30.68200916615432, 76.75404043139179),(30.693376079146592, 76.74425573290546),(30.686290368409434, 76.7330977434035),(30.698247203570425, 76.7235705369826),(30.701346882012448, 76.72880620897968),(30.708283896764318, 76.72365636767108),(30.719942861489645, 76.71318502367694),(30.723336978702484, 76.71867818773944),(30.73528922266025, 76.70940847338397),(30.731157750395077, 76.70202703417499),(30.727616347484997, 76.69807882250507),(30.722451568340254, 76.70288534105975),(30.715072832446122, 76.69104070604999),(30.709169437212882, 76.69601888598163),(30.70164208429022, 76.6833159440871),(30.695737867146164, 76.68949575365741),(30.688799950442366, 76.6778227800246),(30.676103748133954, 76.68846578539569),(30.68348546423622, 76.69945211352069),(30.67772777408624, 76.70408697069843),(30.680385212195993, 76.70906515063007),(30.673889123283725, 76.71472997606952),(30.667392597485662, 76.71764821947772),(30.672855614310695, 76.72623128832538),(30.66606370884733, 76.73155279101093),(30.674627337208847, 76.74288244188983),(30.676251387985264, 76.74717397631366)','zoom_level'=> 13,'client_id'=> $client['code']),
                       array('name'=> 'Panchkula','description'=> 'This is Panchkula geo','geo_array'=> '(30.719341489856117, 76.8238625746167),(30.719636637067353, 76.8381104689038),(30.712848022648412, 76.8432603102124),(30.706796898328488, 76.87312938980224),(30.69528395535308, 76.87055446914795),(30.682588605692853, 76.86351635269287),(30.66413308625118, 76.84549190811279),(30.659407906461745, 76.82489254287842),(30.658521909531984, 76.81974270156982),(30.67860251205408, 76.8077264051831),(30.683031494829706, 76.81047298721435),(30.684803031063623, 76.82180263809326),(30.68465540428553, 76.82884075454834),(30.687312651756983, 76.83416225723388),(30.696169615118347, 76.8319306593335),(30.698383728974065, 76.83399059585693),(30.701040598547177, 76.83296062759521),(30.708125226428436, 76.83502056411865),(30.708125226428436, 76.82952740005615),(30.712110100982148, 76.8238625746167),(30.712405270326148, 76.8216309767163)','zoom_level'=> 13,'client_id'=> $client['code']));
        $prefence = array(array('client_id'=>$client['code'],'theme'=> 'light','distance_unit'=> 'metric','currency_id'=> 147,'language_id'=> 1,'acknowledgement_type' => 'acceptreject','date_format'=> 'm/d/Y','time_format'=> 12,'map_type'=>'google','email_plan'=> 'free', 'agent_name'=> 'Agent'));
        $auto     = array(array('client_id'=>$client['code'],'manual_allocation'=> 1,'auto_assign_logic'=> 'send_to_all','request_expiry'=> 30,'number_of_retries'=> 2,'start_radius' => 10.0,'start_before_task_time'=> 10,'increment_radius'=> 10.00,'maximum_radius'=>100.00,'maximum_batch_size'=> 10,'maximum_task_per_person'=>10,'self_assign'=>0,'maximum_cash_at_hand_per_person'=>5000));
        try {
           
            $schemaName = 'db_' . $client['database_name'] ?: config("database.connections.mysql.database");
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

            $query = "CREATE DATABASE $schemaName;";

            DB::statement($query);


            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            Artisan::call('migrate', ['--database' => $schemaName, '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => $schemaName]);
            DB::connection($schemaName)->table('clients')->insert($client);
            DB::connection($schemaName)->table('client_preferences')->insert($prefence);
            DB::connection($schemaName)->table('teams')->insert($teams);
            DB::connection($schemaName)->table('geos')->insert($geo);
            DB::connection($schemaName)->table('allocation_rules')->insert($auto);
            
            Artisan::call('db:seed', ['--class' => 'CreateAgentSeeder', '--database' => $schemaName, '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'CreateGeoSeeder', '--database' => $schemaName, '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'createPricingRule', '--database' => $schemaName, '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'createTaskProof', '--database' => $schemaName, '--force' => true]);
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
           return $ex->getMessage();
        }
    }
}
