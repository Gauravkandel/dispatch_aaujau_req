<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Model\AgentSmsTemplate;

class DriverSMSTemplateController extends BaseController
{
    use ApiResponser;

    public function index(){
        $templates = AgentSmsTemplate::all();
        return view('auth.cms.agent-sms.index', compact('templates'));
    }
    public function show(Request $request, $domain = '', $id){
        $templates =  AgentSmsTemplate::where('id', $id)->first();
        return $this->success($templates);
    }
    public function update(Request $request, $id){
        $rules = array(
            // 'subject' => 'required',
            'content' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $template = AgentSmsTemplate::where('id', $request->template_id)->firstOrFail();
        // $template->subject = $request->subject;
        $template->content = $request->content;
        $template->save();
        return $this->success($template, 'SMS Template Updated Successfully.');
    }
}
