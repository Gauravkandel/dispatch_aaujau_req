<?php

namespace App\Http\Middleware;

use App\Model\Permissions;
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use Redirect;
use Closure;
use URL;
use Auth;
class CheckManagerPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if (Auth::user()->is_superadmin == 0) {
                    $currentPath = \Request::path();
                    if ($currentPath == "profile") {
                    } else {
                        $sub_admin_per = false;
                        $permission_exist = false;
                        $check_url = Request::path();
                        $per_url = explode('/', $check_url);
                        $per_url= preg_replace("/[^a-zA-Z]+/", "", $per_url[0]);
                        $url_path= strtolower($per_url);
                        $check_if_under_permision = Permissions::select('*','name as name_code')->get()->pluck('name_code');
                        // if (in_array($url_path,$check_if_under_permision)){
                        //     $permission_exist = true;
                        //     dd('ok');
                        // }

                        if ($permission_exist == true) {
                            foreach (Auth::user()->getAllPermissions as $key => $value) {
                                if ($value->name_code == $url_path) {
                                    $sub_admin_per = true;
                                }
                            }
                        } else {
                            $sub_admin_per = true;
                        }
                            
    
                        if ($sub_admin_per == false) {
                            return Redirect::route('profile.index');
                        }
                    }
                
            }
        }
        return $next($request);
        
    }
}    
