<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Jobs\UpdatePassword;
use Crypt;

class ClientProfileController extends Controller
{
    public function edit($id)
    {
        $client = Client::find($id);
        return view('godpanel/update-client')->with('client', $client);
    }

    public function update(Request $request, $id)
    {
    }

    public function changePassword(Request $request)
    {
        $id = Auth::user()->id;

        $user = Auth::user(); // Client::where('id', 1)->first();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                'password'         => Hash::make($request->password),
                'confirm_password' => Crypt::encryptString($request->password),
            ])->save();
            $password['password']         = Hash::make($request->password);
            $password['confirm_password'] = Crypt::encryptString($request->password);
            $client = 'empty';
            // $this->dispatchNow(new UpdatePassword($password, $client));
            $request->session()->flash('success', 'Password changed');
            return redirect()->back()->with('success', 'Password Changed successfully!');
        } else {
            $request->session()->flash('error', 'Wrong Old Password');
            return redirect()->back();
        }
    }
}
