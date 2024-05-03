<?php

namespace App\Http\Middleware;

use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use Redirect;
use Closure;
use URL;
class CheckGodPanel
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
        $domain = $request->getHost();
        $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $domain);
        $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $domain);
        if($domain == env('Main_Domain')){
          return $next($request);
        }
        return redirect()->to($domain);
    }
}
