<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "vnpay_respont",
        "payment/vnpay/notify",
        "payment/vnpay/api",
        "payment/ccavenue/pay",
        "payment/ccavenue/success",
        "payment/gateway/returnResponse"
    ];
}
