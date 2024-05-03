<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\AllocationRule;
use App\Model\Client;
use App\Model\ClientPreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preference = ClientPreference::where('client_id', Auth::user()->code)->first();

        $allocation = AllocationRule::where('client_id', Auth::user()->code)->first();
        ;
      
        
        return view('auto-allocation')->with([
            'allocation' => $allocation,
            'preference'=> $preference
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'request_expiry' => ['required'],
            'number_of_retries' => ['required'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $validator = $this->updateValidator($request->all())->validate();
        $request['manual_allocation'] = $request->manual_allocation ?? 'n';
        $updatePreference = AllocationRule::updateOrCreate([
            'client_id' => $id
        ], $request->all());
        
        return redirect()->back()->with('success', 'Allocation updated successfully!');
    }

    public function updateAllocation(Request $request, $domain = '', $id)
    {
        $updatePreference = ClientPreference::updateOrCreate([
            'client_id' => $id
        ], $request->all());
        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
