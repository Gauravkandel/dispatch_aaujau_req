<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserProfile extends Controller
{

    /**
     * Validation method for clients data
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
        ]);
    }
    
    /**
     * Store data in clients table
    */
    public function SaveRecord(Request $request)
    {
        $this->validator($request->all())->validate();
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
        ];

        $client = Client::create($data);

        return response()->json([
                'status'=>'success',
                'message' => 'User created Successfully',
                'data' => $client
            ]);
    }
}
