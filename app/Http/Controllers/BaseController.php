<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\{Client, ClientPreference};
use Illuminate\Support\Facades\Storage;
use Session;

class BaseController extends Controller
{
    public function getGatewayConnectResponse(Request $request, $domain='')
    {
        return view('pages.gateway-response');
    }
}
