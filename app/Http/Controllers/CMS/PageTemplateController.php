<?php

namespace App\Http\Controllers\CMS;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Model\{Cms};

class PageTemplateController extends BaseController
{
    use ApiResponser;

    public function index(){
        $templates = Cms::all();
        return view('auth.cms.pages.index', compact('templates'));
    }
    public function show(Request $request, $domain = '', $id){
        $templates =  Cms::where('id', $id)->first();
        return $this->success($templates);
    }
    public function update(Request $request, $id){
        $rules = array(
            'content' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $template = Cms::where('id', $request->template_id)->firstOrFail();
        $template->content = $request->content;
        $template->save();
        return $this->success($template, 'SMS Template Updated Successfully.');
    }
}
