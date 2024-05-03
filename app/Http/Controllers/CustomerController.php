<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Customer;
use App\Model\TagCustomer;
use App\Model\Location;
use DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\CustomerExport;
use Excel;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers         = Customer::orderBy('created_at', 'DESC')->get();
        $inActiveCustomers = count($customers->where('status', 'In-Active'));
        $activeCustomers   = count($customers->where('status', 'Active'));
        $customersCount    = count($customers);
        return view('Customer.customer')->with(['customersCount'=>$customersCount, 'activeCustomers'=>$activeCustomers, 'inActiveCustomers'=>$inActiveCustomers]);
    }

    public function customerFilter(Request $request)
    {
        $customers = Customer::orderBy('created_at', 'DESC')->get();
        return Datatables::of($customers)
                ->editColumn('action', function ($customers) use ($request) {
                    $action = '<div class="form-ul" style="width: 60px;">
                                <div class="inner-div"> <a href="javascript:void(0)" userId="'.$customers->id.'" class="action-icon editIcon"> <i class="mdi mdi-square-edit-outline"></i></a></div>';
                    $action .='</div>';
                    return $action;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request){
                            if (!empty($row['name']) && Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                                return true;
                            }else if (!empty($row['email']) && Str::contains(Str::lower($row['email']), Str::lower($request->get('search')))) {
                                return true;
                            }else if (!empty($row['dial_code']) && Str::contains(Str::lower($row['dial_code']), Str::lower($request->get('search')))) {
                                return true;
                            }else if (!empty($row['phone_number']) && Str::contains(Str::lower($row['phone_number']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->make(true);
    }

    public function customersExport(){
        $header = [
            [
             'Sr. No.',
             'Name',
             'Email',
             'Phone Number',
             'Status'
            ]
          ];
          $data = array();
          $customers = Customer::orderBy('created_at', 'DESC')->get();
          if(!empty($customers)){
            $i = 1;
            foreach ($customers as $key => $value) {
              $ndata = [];
              $ndata[] = $i;
              $ndata[] = $value->name;
              $ndata[] = $value->email;
              $ndata[] = $value->phone_number;
              $ndata[] = $value->status;
              $data[] = $ndata;
              $i++;
            }
          }
        return Excel::download(new CustomerExport($data, $header), "customers.xlsx");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //not in use
        return view('Customer/add-customer');
    }

    /**
     * Validation method for customer
     */
    private function validationRules($id = '')
    {
        $rules = [
            'name' => "required|string|max:50",
        ];
        if ($id != '') {
            $rules['email'] = 'required|email|unique:customers,email,' . $id;
            $rules['phone_number'] = 'required|unique:customers,phone_number,' . $id;
        } else {
            $rules['email'] = 'required|email|unique:customers,email';
            $rules['phone_number'] = 'required|unique:customers,phone_number';
        }
        return $rules;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        $rule = $this->validationRules();
        $validation  = Validator::make($request->all(), $rule)->validate();

        //pr($request);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'dial_code' => $request->dialCode,
        ];


        $customer = Customer::create($data);
        if (isset($request->short_name)) {
            foreach ($request->short_name as $key => $value) {
                if (isset($value) && $value != null) {
                    $datas = [
                        'short_name' => $value,
                        'address'    => (!empty($request->address[$key])) ? $request->address[$key] : 'unnamed',
                        'post_code'  => $request->post_code[$key],
                        'latitude'    => $request->latitude[$key],
                        'longitude'  => $request->longitude[$key],
                        'customer_id' => $customer->id,
                        'phone_number' => $request->address_phone_number[$key],
                        'email' => $request->address_email[$key],
                        'flat_no'  => $request->flat_no[$key],
                        // 'due_after' => $request->due_after[$key],
                        // 'due_before' => $request->due_before[$key],
                    ];
                    $Loction = Location::create($datas);
                }
            }
        }

        if ($customer->wasRecentlyCreated) {
            return response()->json([
                'status' => 'success',
                'message' => __('Customer created Successfully!'),
                'data' => $customer
            ]);
        }
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
    public function edit($domain = '', $id)
    {
        $customer = Customer::where('id', $id)->with('location')->first();
        $returnHTML = view('Customer.form')->with('customer', $customer)->render();

        return response()->json(array('success' => true, 'html' => $returnHTML, 'addFieldsCount' => $customer->location->count()));
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
        $rule = $this->validationRules($id);
        $customer = Customer::find($id);
        $validation  = Validator::make($request->all(), $rule)->validate();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'dial_code' => $request->dialCode,
        ];

        $customer->update($data);
        if (isset($request->short_name)) {
            foreach ($request->short_name as $key => $value) {
                if (isset($value) && $value != null) {
                    if (is_array($request->location_id) && array_key_exists($key, $request->location_id)) {
                        $location = Location::find($request->location_id[$key]);
                        if ($location) {
                            $location->short_name = $value;
                            $location->address = $request->address[$key];
                            $location->post_code = $request->post_code[$key];
                            $location->latitude = $request->latitude[$key];
                            $location->longitude = $request->longitude[$key];
                            $location->phone_number = $request->address_phone_number[$key];
                            $location->email = $request->address_email[$key];
                            $location->flat_no = $request->flat_no[$key];
                            // $location->due_after = $request->due_after[$key];
                            // $location->due_before = $request->due_before[$key];

                            $location->save();
                        }
                    } else {
                        $datas = [
                            'short_name' => $value,
                            'address'    => (!empty($request->address[$key])) ? $request->address[$key] : 'unnamed',
                            'post_code'  => $request->post_code[$key],
                            'latitude'    => $request->latitude[$key],
                            'longitude'  => $request->longitude[$key],
                            'customer_id' => $customer->id,
                            'phone_number' => $request->address_phone_number[$key],
                            'email' => $request->address_email[$key],
                            'flat_no'  => $request->flat_no[$key],
                            // 'due_after' => $request->due_after[$key],
                            // 'due_before' => $request->due_before[$key],


                        ];
                        $Loction = Location::create($datas);
                    }
                }
            }
        }


        if ($customer) {
            return response()->json([
                'status' => 'success',
                'message' => __('Customer updated Successfully!'),
                'data' => $customer
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        Customer::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }


    //this function for change status of customer active/in-active

    public function changeStatus(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->status = $request->status;
        $customer->save();
        return response()->json(['success' => __('Status change successfully.')]);
    }

    public function changeLocation(Request $request)
    {
        $locationid = $request->locationid;
        $location = Location::find($request->locationid);
        if ($location) {
            $location->location_status = 0;
            $location->save();
            echo "removed";
        } else {
            echo "failed";
        }
    }
}
