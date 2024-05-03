<?php

namespace App\Http\Controllers\Api;


use Log;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Api\BaseController;

// use App\Http\Controllers\Api\v1\OrderController;
use App\Model\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax};
use Illuminate\Support\Facades\Auth as FacadesAuth;

class RazorpayGatewayController extends BaseController
{
    use ApiResponser;
    public $API_KEY;
    public $API_SECRET_KEY;
    public $test_mode;
    public $api;

    public function __construct()
    {
        $razorpay_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'razorpay')->where('status', 1)->first();
        $creds_arr = json_decode($razorpay_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $api_secret_key = (isset($creds_arr->api_secret_key)) ? $creds_arr->api_secret_key : '';
        $this->test_mode = (isset($razorpay_creds->test_mode) && ($razorpay_creds->test_mode == '1')) ? true : false;

        $this->API_KEY = $api_key;
        $this->API_SECRET_KEY = $api_secret_key;
        $this->api = new Api($api_key, $api_secret_key);
    }

    public function razorpayPurchase(Request $request)
    {
        try {
            $user = Auth::user();
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $amount = $this->getDollarCompareAmount($request->amount);
            $amount = filter_var($amount, FILTER_SANITIZE_NUMBER_INT);
            $order_number = $request->order_number;
            if (!isset($order_number)) {
                $order_number = 0;
            }
            $api_key = $this->API_KEY;
            return $this->success(url('/payment/razorpay/view?amount=' . $amount . '&order=' . $order_number . '&api_key=' . $api_key));
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage(), 400);
        }
    }

    public function razorpayCompletePurchase(Request $request, $domain, $amount, $order = null)
    {

        try {
            $user = Auth::user();
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $amount = $this->getDollarCompareAmount($amount);
            $amount = filter_var($amount, FILTER_SANITIZE_NUMBER_INT);

            // $returnUrlParams = '?gateway=razorpay&order=' . $request->order_number;

            $returnUrl = route('order.return.success');
            if ($request->payment_form == 'wallet') {
                $returnUrl = route('user.wallet');
            }
            //$notifyUrlParams = '?gateway=paylink&amount=' . $amount . '&order=' . $order_number;

            $orderData = [

                'amount'          => $amount / 100,

                'currency'        => 'INR'
            ];

            // $razorpayOrder = $this->api->order->create($orderData);
            //dd($razorpayOrder);
            $payment = $this->api->payment->fetch($request->razorpay_payment_id)->capture($orderData);

            if ($payment['status'] == 'captured') {
                return $this->razorpayNotify($payment, $amount, $order, $orderData);
            } else {
                return $this->razorpayNotify_fail($payment, $amount, $order, $orderData);
            }
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage(), 400);
        }
    }
}
