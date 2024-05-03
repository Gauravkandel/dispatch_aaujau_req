<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Http\Traits\ToasterResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use App\Model\{Client, ClientPreference, PaymentOption, PayoutOption};

class PaymentOptionController extends BaseController
{
    // use ToasterResponser;

    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_codes = array('razorpay', 'stripe','vnpay','ccavenue', 'khalti');
        $payout_codes = array('cash', 'stripe', 'bank_account_m_india');
        $payOption = PaymentOption::whereIn('code', $payment_codes)->get();
        $payoutOption = PayoutOption::whereIn('code', $payout_codes)->get();
        return view('payoption/index')->with(['payOption' => $payOption, 'payoutOption' => $payoutOption]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $status = 0;
        $msg = $request->method_name . ' deactivated successfully!';

        $saved_creds = PaymentOption::select('credentials')->where('id', $id)->first();

        if ((isset($saved_creds)) && (!empty($saved_creds->credentials))) {
            $json_creds = $saved_creds->credentials;
        } else {
            $json_creds = NULL;
        }

        if ($request->has('active') && $request->active == 'on') {
            $status = 1;
            $msg = $request->method_name . ' activated successfully!';

            if (strtolower($request->method_name) == 'paypal') {
                $json_creds = json_encode(array(
                    'username' => $request->paypal_username,
                    'password' => $request->paypal_password,
                    'signature' => $request->paypal_signature,
                ));
            } else if (strtolower($request->method_name) == 'stripe') {
                $json_creds = json_encode(array(
                    'api_key' => $request->stripe_api_key
                ));
            }
        }

        PaymentOption::where('id', $id)->update(['status' => $status, 'credentials' => $json_creds]);

        return redirect()->back()->with('success', $msg);
    }

    public function updateAll(Request $request, $domain = '')
    {
        $msg = __('Payment options have been saved successfully!');
        $method_id_arr = $request->input('method_id');
        $method_name_arr = $request->input('method_name');
        $active_arr = $request->input('active');
        $test_mode_arr = $request->input('sandbox');

        foreach ($method_id_arr as $key => $id) {
            $saved_creds = PaymentOption::select('credentials')->where('id', $id)->first();
            if ((isset($saved_creds)) && (!empty($saved_creds->credentials))) {
                $json_creds = $saved_creds->credentials;
            } else {
                $json_creds = NULL;
            }

            $status = 0;
            $test_mode = 0;
            if ((isset($active_arr[$id])) && ($active_arr[$id] == 'on')) {
                $status = 1;

                if ((isset($test_mode_arr[$id])) && ($test_mode_arr[$id] == 'on')) {
                    $test_mode = 1;
                }

                if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'paypal')) {
                    $validatedData = $request->validate([
                        'paypal_username'       => 'required',
                        'paypal_password'       => 'required',
                        'paypal_signature'      => 'required',
                    ]);
                    $json_creds = json_encode(array(
                        'username' => $request->paypal_username,
                        'password' => $request->paypal_password,
                        'signature' => $request->paypal_signature,
                    ));
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'stripe')) {
                    $validatedData = $request->validate([
                        'stripe_api_key'        => 'required',
                        'stripe_publishable_key' => 'required'
                    ], [
                        'stripe_api_key.required' => 'Stripe secret key field is required'
                    ]);
                    $stripe_arr = array(
                        'api_key' => $request->stripe_api_key,
                        'publishable_key' => $request->stripe_publishable_key
                    );
                    if(isset($request->stripe_client_id)){
                        $stripe_arr['client_id'] = $request->stripe_client_id;
                    }
                    $json_creds = json_encode($stripe_arr);
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'yoco')) {
                    $validatedData = $request->validate([
                        'yoco_secret_key'        => 'required',
                        'yoco_public_key' => 'required'
                    ], [
                        'yoco_secret_key.required' => 'Yoco secret key field is required'
                    ]);
                    $json_creds = json_encode(array(
                        'secret_key' => $request->yoco_secret_key,
                        'public_key' => $request->yoco_public_key
                    ));
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'paystack')) {
                    $validatedData = $request->validate([
                        'paystack_secret_key' => 'required',
                        'paystack_public_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'secret_key' => $request->paystack_secret_key,
                        'public_key' => $request->paystack_public_key
                    ));
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'paylink')) {
                    $validatedData = $request->validate([
                        'paylink_api_key' => 'required',
                        'paylink_api_secret_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'api_key' => $request->paylink_api_key,
                        'api_secret_key' => $request->paylink_api_secret_key
                    ));
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'razorpay')) {
                    $validatedData = $request->validate([
                        'razorpay_api_key' => 'required',
                        'razorpay_api_secret_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'api_key' => $request->razorpay_api_key,
                        'api_secret_key' => $request->razorpay_api_secret_key
                    ));
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'payfast')) {
                    $validatedData = $request->validate([
                        'payfast_merchant_id' => 'required',
                        'payfast_merchant_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'merchant_id' => $request->payfast_merchant_id,
                        'merchant_key' => $request->payfast_merchant_key,
                        'passphrase' => $request->payfast_passphrase
                    ));
                } else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'mobbex')) {
                    $validatedData = $request->validate([
                        'mobbex_api_key' => 'required',
                        'mobbex_api_access_token' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'api_key' => $request->mobbex_api_key,
                        'api_access_token' => $request->mobbex_api_access_token
                    ));
                }else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'gcash')) {
                    $validatedData = $request->validate([
                        'gcash_public_key' => 'required',
                    ]);
                    $json_creds = json_encode(array(
                        'public_key' => $request->gcash_public_key,
                    ));
                }else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'simplify')) {
                    $validatedData = $request->validate([
                        'simplify_public_key' => 'required',
                        'simplify_private_key' => 'required',
                    ]);
                    $json_creds = json_encode(array(
                        'public_key' => $request->simplify_public_key,
                        'private_key' => $request->simplify_private_key,
                    ));
                }
                else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'square')) {
                    $validatedData = $request->validate([
                        'square_application_id' => 'required',
                        'square_access_token' => 'required',
                        'square_location_id' => 'required',
                    ]);
                    $json_creds = json_encode(array(
                        'application_id' => $request->square_application_id,
                        'api_access_token' => $request->square_access_token,
                        'location_id' => $request->square_location_id,
                    ));
                }
                else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'ozow')) {
                    $validatedData = $request->validate([
                        'ozow_site_code' => 'required',
                        'ozow_private_key' => 'required',
                        'ozow_api_key' => 'required',
                    ]);
                    $json_creds = json_encode(array(
                        'site_code' => $request->ozow_site_code,
                        'private_key' => $request->ozow_private_key,
                        'api_key' => $request->ozow_api_key,
                    ));
                }
                else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'vnpay')) {
                    $validatedData = $request->validate([
                        'vnpay_website_id' => 'required',
                        'vnpay_server_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'vnpay_website_id' => $request->vnpay_website_id,
                        'vnpay_server_key' => $request->vnpay_server_key
                    ));
                }else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'ccavenue')) {
                    $validatedData = $request->validate([
                        'ccavenue_merchant_id' => 'required',
                        'ccavenue_access_code' => 'required',
                        'ccavenue_enc_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'merchant_id' => $request->ccavenue_merchant_id,
                        'access_code' => $request->ccavenue_access_code,
                        'enc_key' => $request->ccavenue_enc_key
                    ));
                }
                else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'khalti')) {
                    $validatedData = $request->validate([
                        'khalti_public_key' => 'required',
                        'khalti_secret_key' => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'api_key' => $request->khalti_public_key,
                        'api_secret_key' => $request->khalti_secret_key
                    ));
                }
            }
            PaymentOption::where('id', $id)->update(['status' => $status, 'credentials' => $json_creds, 'test_mode' => $test_mode]);
        }
        // $toaster = $this->successToaster(__('Success'), $msg);
        return redirect()->back()->with('success', $msg);
    }


    public function payoutUpdateAll(Request $request, $domain = '')
    {
        $msg = __('Payout options have been saved successfully!');
        $method_id_arr = $request->input('method_id');
        $method_name_arr = $request->input('method_name');
        $active_arr = $request->input('active');
        $test_mode_arr = $request->input('sandbox');

        foreach ($method_id_arr as $key => $id) {
            $saved_creds = PayoutOption::select('credentials')->where('id', $id)->first();
            if ((isset($saved_creds)) && (!empty($saved_creds->credentials))) {
                $json_creds = $saved_creds->credentials;
            } else {
                $json_creds = NULL;
            }

            $status = 0;
            $test_mode = 0;
            if ((isset($active_arr[$id])) && ($active_arr[$id] == 'on')) {
                $status = 1;

                if ((isset($test_mode_arr[$id])) && ($test_mode_arr[$id] == 'on')) {
                    $test_mode = 1;
                }

                if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'stripe')) {
                    $validatedData = $request->validate([
                        'stripe_payout_secret_key'      => 'required',
                        'stripe_payout_publishable_key' => 'required',
                        'stripe_payout_client_id'       => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'secret_key' => $request->stripe_payout_secret_key,
                        'publishable_key' =>  $request->stripe_payout_publishable_key,
                        'client_id' => $request->stripe_payout_client_id
                    ));
                }
                else if ((isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'razorpay')) {
                    $validatedData = $request->validate([
                        'razorpay_payout_api_key'       => 'required',
                        'razorpay_payout_secret_key'    => 'required'
                    ]);
                    $json_creds = json_encode(array(
                        'api_key' => $request->razorpay_payout_api_key,
                        'secret_key' => $request->razorpay_payout_secret_key
                    ));
                }
            }
            PayoutOption::where('id', $id)->update(['status' => $status, 'credentials' => $json_creds, 'test_mode' => $test_mode]);
        }
        // $toaster = $this->successToaster(__('Success'), $msg);
        return redirect()->back()->with('success', $msg);
    }

    public function getGatewayReturnResponse(Request $request)
    {
        return view('payoption.gatewayReturnResponse');
    }

}
