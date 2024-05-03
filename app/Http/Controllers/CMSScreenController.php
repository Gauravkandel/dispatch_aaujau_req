<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Cms;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class CMSScreenController extends Controller
{

    public function terms_n_condition()
    {
        $cms = Cms::all('content');
        return view('cms_screen.terms_n_condition')->with(['cms' => $cms]);
    }

    public function privacy_policy()
    {
        $cms = Cms::all('content');
        return view('cms_screen.privacy_policy')->with(['cms' => $cms]);
    }
}
