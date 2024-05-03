<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //\App\Http\Middleware\RouteDynamic::class,

        ],

        'api' => [
            'throttle:500,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'database' => \App\Http\Middleware\DatabaseDynamic::class,
        'check'    =>  \App\Http\Middleware\CheckClient::class,
        'dbCheck'    => \App\Http\Middleware\DbChooserApi::class,
        'AppAuth'    =>\App\Http\Middleware\AppAuth::class,
        'domain'     =>\App\Http\Middleware\CustomDomain::class,
        'subdomain'  => \App\Http\Middleware\SubdomainMiddleware::class,
        'CheckGodPanel'  => \App\Http\Middleware\CheckGodPanel::class,
        'CheckManagerPermission' => \App\Http\Middleware\CheckManagerPermission::class,
        'ConnectDbFromOrder' => \App\Http\Middleware\ConnectDbFromOrder::class,
        'switchLanguage'  => \App\Http\Middleware\CheckLocale::class,
        'apiLocalization'  => \App\Http\Middleware\ApiLocalization::class,
        
    ];
}
