<?php

namespace App\Http\Controllers\CMS;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Model\{EmailTemplate};

class EmailTemplateController extends BaseController
{
    use ApiResponser;

    public function index(){
        $email_templates = EmailTemplate::all();
        return view('auth.cms.email.index', compact('email_templates'));
    }
    public function show(Request $request, $domain = '', $id){
        $email_template =  EmailTemplate::where('id', $id)->first();
        return $this->success($email_template);
    }
    public function update(Request $request, $id){
        $rules = array(
            'subject' => 'required',
            'content' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $email_template = EmailTemplate::where('id', $request->email_template_id)->firstOrFail();
        $email_template->subject = $request->subject;
        $email_template->content = $request->content;
        $email_template->save();
        return $this->success($email_template, 'Email Template Updated Successfully.');
    }
}
