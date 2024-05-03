<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class ApiLocalization
{

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next)
    {
        // Check header request and determine localizaton
        $local = ($request->hasHeader('language')) ? $request->header('language') : 'en';

        if (!array_key_exists($local, $this->app->config->get('app.supported_languages'))) {
            app()->setLocale($local);
        }
        
        return $next($request);
    }
}
