<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLogin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [  
                'phone_number' => 'required|numeric',
                'device_type' => 'required|string',
                'device_token' => 'required|string',
                'otp' => 'required|string|min:6|max:6',
    
        ];
    }
}
