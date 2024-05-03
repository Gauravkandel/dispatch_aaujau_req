<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSocketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'title' => 'required',
            'domain_url' => 'required|unique:chat_sockets',
        ];

    }
     public function messages(){
        return [
            "title.required" => __('The title field is required.'),
            "domain_url.required" => __("The Domain url field is required."),
        ];
    }
}
