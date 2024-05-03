<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        \URL::defaults(['subdomain' => request('subdomain')]);
        \URL::defaults(['domain' => request('domain')]);

        return $next($request);
    }
}
