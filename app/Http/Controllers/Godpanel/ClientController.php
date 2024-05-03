<?php

namespace App\Http\Controllers\Godpanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ClientPreference;
use App\Model\Currency;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\ProcessClientDatabase;
use App\Model\Client;
use App\Model\Cms;
use App\Model\SubClient;
use App\Model\TaskProof;
use App\Model\TaskType;
use App\Model\SmtpDetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Session;
use Illuminate\Support\Facades\Storage;
use Crypt;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Traits\GlobalFunction;
use Log;

class ClientController extends Controller
{
    use GlobalFunction;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::where('is_deleted', 0)->orderBy('created_at', 'DESC')->paginate(300);
        return view('godpanel/client')->with(['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ChatSocketUrl = GlobalFunction::socketDropDown();
        return view('godpanel/update-client')->with(['ChatSocketUrl' =>$ChatSocketUrl]);
    }

    /**
     * Validation method for clients data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'phone_number' => ['required'],
            'password' => ['required'],
            'database_name' => ['required','unique:clients,database_name'],
            'custom_domain' => ['nullable','unique:clients,custom_domain'],
            'sub_domain' => ['required','min:3','unique:clients,sub_domain'],
            'logo' => ['image'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all())->validate();
        DB::beginTransaction();
        try {
            $getFileName = null;

            // Handle File Upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                $s3filePath = '/assets/Clientlogo/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                $getFileName = $path;
            }

            $database_name = preg_replace('/\s+/', '', $request->database_name);
            $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_password' => Crypt::encryptString($request->password),
            'phone_number' => $request->phone_number,
            'database_name' => $database_name,
            'company_name' => $request->company_name,
            'company_address'  => $request->company_address,
            'database_username' => env('DB_USERNAME'),
            'database_password' => env('DB_PASSWORD'),
            'logo' => isset($getFileName) ? $getFileName : 'assets/Clientlogo/5ff41c4b5a9f0.png/KQb50SOKZckXbcmMBXgqz3pqfCZcOTpkpljs8sJq.png',
            'status'=> 1,
            'timezone' => $request->timezone ? $request->timezone : 'America/New_York',
            'custom_domain'=> $request->custom_domain??'',
            'sub_domain'   => $request->sub_domain,
            'socket_url' =>$request->socket_url
        ];
            $data['code'] = $this->randomString();

            $client = Client::create($data);

            # if submit custom domain from god panel
            if ($request->custom_domain && $request->custom_domain != $client->custom_domain) {
                try {
                    $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $request->custom_domain);
                    $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $request->custom_domain);
                    $my_url =   $request->custom_domain;
                    $data1 = [
                        'domain' => $my_url
                    ];
                    
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "localhost:3000/add_subdomain",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($data1),
                        CURLOPT_HTTPHEADER => array(
                           "content-type: application/json",
                        ),
                    ));
                    
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    
                    curl_close($curl);
                    if ($err) {
                        return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $err]));
                    }
                } catch (Exception $e) {
                    return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $e->getMessage()]));
                }
                $exists = Client::where('id', '!=', $client->id)->where('custom_domain', $request->custom_domain)->count();
                if ($exists) {
                    return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
                } else {
                    Client::where('id', $client->id)->update(['custom_domain' => $request->custom_domain]);
                }
            }
            if ($request->sub_domain == 'api' ||  $request->sub_domain == 'god'  ||  $request->sub_domain == 'godpanel'  ||  $request->sub_domain == 'admin') {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['sub_domain' => 'Sub Domain name "' . $request->sub_domain . '" is not available. Please select a different sub domain']));
            }

            Cache::set($database_name, $data);

            $this->dispatchNow(new ProcessClientDataBase($client->id));
            DB::commit();
       
            return redirect()->route('client.index')->with('success', 'Client Added successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('client.index')->with('error', $e->getMessage());
        }
    }

    private function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while (Client::where('code', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        return redirect()->back()->with(['getClient' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ChatSocketUrl = GlobalFunction::socketDropDown();
        $client = Client::find($id);
        return view('godpanel/update-client')->with(['client'=>$client, 'ChatSocketUrl' =>$ChatSocketUrl]);
    }

    /**
     * Validation method for clients Update
     */
    protected function updateValidator(array $data, $id)
    {
        return Validator::make($data, [
           'sub_domain' => ['required','min:3',\Illuminate\Validation\Rule::unique('clients')->ignore($id)],
           'custom_domain' => ['nullable',\Illuminate\Validation\Rule::unique('clients')->ignore($id)],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->updateValidator($request->all(), $id)->validate();
        DB::beginTransaction();
        try {
            $getClient = Client::find($id);
            $getFileName = $getClient->logo;
            $removeDataFromRedis = Cache::forget($getClient->database_name);

            // Handle File Upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                $s3filePath = '/assets/Clientlogo/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                $getFileName = $path;
            }
        
            $data = [
            'database_name' => $getClient['database_name'],
            'custom_domain' => $request->custom_domain,
            'sub_domain'   => $request->sub_domain,
            'socket_url' =>$request->socket_url
        ];
        
            $client = Client::where('id', $id)->update($data);
            $saveDataOnRedis = Cache::set($data['database_name'], $data);
            $insetinCiientDb = $this->connectionToClientDB($getClient, $request);
            if ($insetinCiientDb != 1) {
                return redirect()->back()->withInput()->with('error', $insetinCiientDb);
            }

            DB::commit();
       
            return redirect()->route('client.index')->with('success', 'Client Updated successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function connectionToClientDB($getClient, $request)
    {
        try {

         # if submit custom domain from god panel
            if ($request->custom_domain && $request->custom_domain != $getClient->custom_domain) {
                try {
                    $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $request->custom_domain);
                    $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $request->custom_domain);
                    $my_url =   $request->custom_domain;
                    $data1 = [
                        'domain' => $my_url
                    ];
                    
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "localhost:3000/add_subdomain",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($data1),
                        CURLOPT_HTTPHEADER => array(
                           "content-type: application/json",
                        ),
                    ));
                    
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    
                    curl_close($curl);
                    if ($err) {
                        return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $err]));
                    }
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }
            if ($request->sub_domain == 'api' ||  $request->sub_domain == 'god'  ||  $request->sub_domain == 'godpanel'  ||  $request->sub_domain == 'admin') {
                return 'Sub Domain name "' . $request->sub_domain . '" is not available. Please select a different sub domain';
            }

            $schemaName = 'db_' . $getClient['database_name'];
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
            DB::connection($schemaName)->table('clients')->update(['custom_domain' => $request->custom_domain, 'sub_domain'   => $request->sub_domain]);
            DB::disconnect($schemaName);
            return 1;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $getClient = Client::where('id', $id)->update(['is_deleted' => 1,'custom_domain' => null,'sub_domain' => null]);
        return redirect()->back()->with('success', 'Client deleted successfully!');
    }


    public function exportDb(Request $request,$databaseName){

        try {

        $client = Client::where('database_name', $databaseName)->first(['name', 'email', 'password', 'phone_number', 'password', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'dark_logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code','sub_domain','database_host'])->toarray();
        $check_if_already = 0;
        $stage = $request->dump_into??'PROD';
        $data = $request->all();
        if($client){
            
            $check_if_already = Client::on($stage)->where(['database_name' => $client['database_name']])->where(['sub_domain' => $client['sub_domain']])->count();
            if($check_if_already == 0){
                $clientData = array();

                foreach ($client as $key => $value) {
                   
                    if($key == 'database_host'){
                        $clientData[$key] = env('DB_HOST_'.$stage);
                    }else{
                        $clientData[$key] = $value;
                    }

                    if($key == 'custom_domain'){
                        $clientData[$key] = '';
                    }

                    

                    
                }

                try {
                    
                    DB::connection($stage)->table('clients')->insert($clientData);
                    return redirect()->route('client.index')->with('success', 'Client Migrated!');
                } catch (Exception $ex) {
                    return redirect()->route('client.index')->with('error', $ex->getMessage());
                  
                }
            }
            else{
                return redirect()->route('client.index')->with('error', 'This client is already exist!!');
            }
        }else{
            return redirect()->route('client.index')->with('error', 'This client not exist!!');
        }

    } catch (Exception $ex) {
        return redirect()->route('client.index')->with('error', $ex->getMessage());
      
    }

    }


    /////////////// *********************** socket url update********************************* ////////////////////////////////////////

    public function socketUpdateAction(Request $request,$id)
    {
      $data = GlobalFunction::checkDbStat($id);
        try {
                DB::connection($data['schemaName'])->beginTransaction();
                $update = DB::table('clients')->where('id',$id)->update(['socket_url' => $request->socket_url]);
                $update_sub = DB::connection($data['schemaName'])->table('clients')->where('id',1)->update(['socket_url' => $request->socket_url]);
                DB::connection($data['schemaName'])->commit();
                return redirect()->route('client.index')->with('success', 'Socket URL updated successfully!');
           
            
        } catch (\PDOException $e) {
            DB::connection($data['schemaName'])->rollBack();
            return redirect()->route('client.index')->with('error', $e->getMessage());
        }
            
            
    }
}
