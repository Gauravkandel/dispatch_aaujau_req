<?php

namespace App\Http\Controllers\Api;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\{BaseController, WalletController};
use App\Model\{Agent, Payment, PaymentOption, Client, ClientPreference, AgentConnectedAccount};

class StripeGatewayController extends BaseController
{

    use ApiResponser;
    public $gateway;
    public $currency;

    public function __construct()
    {
        $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live

        $primaryCurrency = ClientPreference::with('currency')->select('currency_id')->where('id', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function stripePurchase(request $request)
    {
        try {
            $user = Auth::user();
            // $amount = $this->getDollarCompareAmount($request->amount);
            $amount = $request->amount;
            $token = $request->input('stripe_token');

            $payment_form = $request->action;

            $saved_payment_method = $this->getSavedUserPaymentMethod($request);
            if (!$saved_payment_method) {
                $customerResponse = $this->gateway->createCustomer(array(
                    'description' => 'Creating Customer',
                    'name' => $user->name,
                    'email' => '',
                    'source' => $token,
                    'metadata' => [
                        'driver_id' => $user->id,
                        'phone_number' => $user->phone_number
                    ]
                ))->send();

                // Find the card ID
                $customer_id = $customerResponse->getCustomerReference();
                if ($customer_id) {
                    $request->request->set('customerReference', $customer_id);
                    $save_payment_method_response = $this->saveUserPaymentMethod($request);
                }
            }else {
                $customer_id = $saved_payment_method->customerReference;
            }

            $postdata = [
                'currency' => $this->currency,
                // 'token' => $token,
                'amount' => $amount,
                'metadata' => [
                    'driver_id' => $user->id,
                    'name'=> $user->name,
                    // 'email'=> $user->email,
                    'phone_number'=> $user->phone_number
                ],
                'customerReference' => $customer_id
            ];

            if($payment_form == 'wallet'){
                $postdata['description'] = 'Wallet Checkout';
            }

            $authorizeResponse = $this->gateway->authorize($postdata)->send();

            // dd($authorizeResponse->isSuccessful());
            if ($authorizeResponse->isSuccessful()) {
                $response = $this->gateway->purchase($postdata)->send();
                
                if ($response->isSuccessful()) {
                    $transactionId = $response->getTransactionReference();
                    $returnUrl = '';

                    if($payment_form == 'cart'){

                    } elseif($payment_form == 'wallet'){
                        $request->request->add(['wallet_amount' => $amount, 'transaction_id' => $transactionId]);
                        $walletController = new WalletController();
                        $walletController->creditAgentWallet($request);
                    }
                    elseif($payment_form == 'tip'){
                        
                    }
                    elseif($payment_form == 'subscription'){
                        
                    }
                    return $this->success('', __('Payment has been completed successfully'), 200);
                }
                else {
                    return $this->error($response->getMessage(), 400);
                }
            }else {
                return $this->error($authorizeResponse->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage(), 400);
        }
    }


    // Verifya and store connected account
    public function verifyOAuthToken(request $request)
    {
        try{
            $user = Auth::user();
            $driver = $request->state;
            if($request->has('code')){
                $code = $request->code;
                $checkIfExists = AgentConnectedAccount::where('agent_id', $driver)->first();
                if($driver > 0){
                    if($checkIfExists){
                        $msg = __('You are already connected to stripe');
                        $toaster = $this->error($msg, 400);
                    }
                    else{
                        // Complete the connection and get the account ID
                        \Stripe\Stripe::setApiKey($this->payout_secret_key);
                        $response = \Stripe\OAuth::token([
                            'grant_type' => 'authorization_code',
                            'code' => $code,
                        ]);

                        // Access the connected account id in the response
                        $connected_account_id = $response->stripe_user_id;
                        $connectdAccount = new AgentConnectedAccount();
                        $connectdAccount->driver_id = $vendor;
                        $connectdAccount->account_id = $connected_account_id;
                        $connectdAccount->payment_option_id = 2;
                        $connectdAccount->is_primary = 1;
                        $connectdAccount->status = 1;
                        $connectdAccount->save();

                        $msg = __('Stripe connect has been enabled successfully');
                        return $this->success($connected_account_id, $msg);
                    }
                }else{
                    $msg = __('Invalid Data');
                    return $this->error($msg, 400);
                }
            }
            else{
                $msg = __('Stripe connect has been declined');
                return $this->error($msg, 400);
            }
        }
        catch(Exception $ex){
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }


    ///// Stripe FPX Payment /////

    public function createStripeFPXPaymentIntent(Request $request, $domain='')
    {
        try{
            ////// Create webhook Endpoint ///////
            $secret_key = stripeFPXPaymentCredentials()->secret_key;
            $stripe = new \Stripe\StripeClient($secret_key);
            
            $webhook_url = 'https://'.$domain.'/payment/webhook/stripe_fpx';
            $webhook_exists = false;

            // $stripe->webhookEndpoints->delete(
            //     'we_1KQXhFA3MquWN79FKLUy0Zzp',
            //     []
            // );
            // $stripe->webhookEndpoints->delete(
            //     'we_1KQXc3A3MquWN79FjGGWHT66',
            //     []
            // );
            // $stripe->webhookEndpoints->delete(
            //     'we_1KQX8gA3MquWN79FmZFGhD9G',
            //     []
            // );
            $endpoints = $stripe->webhookEndpoints->all();

            foreach($endpoints->data as $obj){
                if($obj->url == $webhook_url){
                    $webhook_exists = true;
                    break;
                }
            }
            
            if(!$webhook_exists){
                $res = $stripe->webhookEndpoints->create([
                    'url' => $webhook_url,
                    'enabled_events' => [
                        'payment_intent.succeeded',
                        'payment_intent.payment_failed'
                    ]
                ]);
            }
            // return $webhook_exists;

            $user = Auth::user();

            $description = '';
            $payment_form = $request->payment_form;
            $amount = $this->getDollarCompareAmount($request->amount);

            $postdata = [
                'payment_method_types' => ['fpx'],
                'amount' => $amount * 100,
                'currency' => 'myr', //$this->currency
                // 'customer' => '',
                'receipt_email' => $user->email ?? '',
                'metadata' => [
                    'user_id' => $user->id,
                    'payment_form' => $payment_form
                ]
            ];

            if($payment_form == 'cart'){
                $user_address = '';
                if($request->has('address_id')){
                    $address_id = $request->address_id;
                    $user_address = UserAddress::where('id', $address_id)->first();
                }
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $order_number = $request->order_number;

                $postdata['description'] = 'Order Checkout';
                $postdata['metadata']['cart_id'] = $cart->id;
                $postdata['metadata']['order_number'] = $order_number;
                $postdata['shipping']['name'] = $user->name;
                $postdata['shipping']['phone'] = $user->dial_code . $user->phone_number;
                if(!empty($user_address)){
                    $postdata['shipping']['address']['line1'] = $user_address->street;
                    $postdata['shipping']['address']['city'] = $user_address->city;
                    $postdata['shipping']['address']['state'] = $user_address->state;
                    $postdata['shipping']['address']['country'] = $user_address->country;
                    $postdata['shipping']['address']['postal_code'] = $user_address->pincode;
                }
            }
            elseif($payment_form == 'wallet'){
                $postdata['description'] = 'Wallet Checkout';
            }
            if($payment_form == 'tip'){
                $postdata['description'] = 'Tip Checkout';
                $order_number = $request->order_number;
                $postdata['metadata']['order_number'] = $order_number;
            }
            elseif($request->payment_form == 'subscription'){
                $postdata['description'] = 'Subscription Checkout';
                $postdata['metadata']['subscription_id'] = $request->subscription_id;
            }
            
            $payment_intent = $stripe = $stripe->paymentIntents->create($postdata);
            
            return $this->successResponse($payment_intent->client_secret);
        }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    public function retrieveStripeFPXPaymentIntent(Request $request)
    {
        if($request->has('payment_intent')){
            if($request->has('redirect_status') && ($request->redirect_status == 'succeeded')){
                // $secret_key = stripeFPXPaymentCredentials()->secret_key;
                // \Stripe\Stripe::setApiKey($secret_key);

                // $payment_intent_id = $request->get('payment_intent');
                // $intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
                // $charges = $intent->charges->data;
                // $transactionId = $cart_id = $payment_form = $order_number = '';
                // $amount = 0;
                // if(count($charges)){
                //     $transactionId = $charges[0]->balance_transaction;
                //     $payment_form = $charges[0]->metadata->payment_form;
                //     $order_nu = $charges[0]->metadata->order_number;
                //     $cart_id = $charges[0]->metadata->cart_id ?? '';
                //     $amount = $charges[0]->amount / 100;
                // }
                
                // dd($charges[0]);

                if($request->payment_form == 'cart'){
                    $order_number = $request->order;
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        // $order->payment_status = 1;
                        // $order->save();
                        // $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                        // if (!$payment_exists) {
                        //     $payment = new Payment();
                        //     $payment->date = date('Y-m-d');
                        //     $payment->order_id = $order->id;
                        //     $payment->transaction_id = $transactionId;
                        //     $payment->balance_transaction = $amount;
                        //     $payment->type = 'cart';
                        //     $payment->save();
    
                        //     // Auto accept order
                        //     $orderController = new OrderController();
                        //     $orderController->autoAcceptOrderIfOn($order->id);
    
                        //     // Remove cart
                        //     Cart::where('id', $cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                        //     CartAddon::where('cart_id', $cart_id)->delete();
                        //     CartCoupon::where('cart_id', $cart_id)->delete();
                        //     CartProduct::where('cart_id', $cart_id)->delete();
                        //     CartProductPrescription::where('cart_id', $cart_id)->delete();
    
                        //     // Send Notification
                        //     if (!empty($order->vendors)) {
                        //         foreach ($order->vendors as $vendor_value) {
                        //             $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                        //             $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                        //             $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                        //         }
                        //     }
                        //     $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
                        //     $super_admin = User::where('is_superadmin', 1)->pluck('id');
                        //     $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);
                        // }
                        $returnUrlParams = ''; //'?gateway=paylink&order=' . $order->id;
                        $returnUrl = route('order.success', $order->id); // route('order.return.success');
                        return Redirect::to(url($returnUrl . $returnUrlParams));
    
                        // Send Email
                        //   $this->successMail();
                    }
                } elseif($request->payment_form == 'wallet'){
                    // $request->request->add(['wallet_amount' => $request->amount, 'transaction_id' => $transactionId]);
                    // $walletController = new WalletController();
                    // $walletController->creditWallet($request);
                    $returnUrl = route('user.wallet');
                    return Redirect::to(url($returnUrl));
                }
                elseif($request->payment_form == 'tip'){
                    // $request->request->add(['order_number' => $request->order, 'tip_amount' => $request->amount, 'transaction_id' => $transactionId]);
                    // $orderController = new OrderController();
                    // $orderController->tipAfterOrder($request);
                    $returnUrl = route('user.orders');
                    return Redirect::to(url($returnUrl));
                }
                elseif($request->payment_form == 'subscription'){
                    // $request->request->add(['payment_option_id' => 9, 'transaction_id' => $transactionId]);
                    // $subscriptionController = new UserSubscriptionController();
                    // $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription);
                    $returnUrl = route('user.subscription.plans');
                    return Redirect::to(url($returnUrl));
                }
            }
            elseif($request->has('redirect_status') && ($request->redirect_status == 'failed')){
                if($request->payment_form == 'cart'){
                    return Redirect::to(route('showCart'))->with('error', 'Your order has been cancelled');
                } elseif($request->payment_form == 'wallet'){
                    return Redirect::to(route('user.wallet'))->with('error', 'Transaction has been cancelled');
                } elseif($request->payment_form == 'tip'){
                    return Redirect::to(route('user.orders'))->with('error', 'Transaction has been cancelled');
                } elseif($request->payment_form == 'subscription'){
                    return Redirect::to(route('user.subscription.plans'))->with('error', 'Transaction has been cancelled');
                }
            }
        }
    }


    public function stripeFPXWebhook(Request $request)
    {
        $secret_key = stripeFPXPaymentCredentials()->secret_key;
        \Stripe\Stripe::setApiKey($secret_key);

        $payload = @file_get_contents('php://input');
        $event = null;
        // \Log::info($payload);
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // \Log::info($paymentIntent);

                $payment_intent_id = $paymentIntent->id;
                $intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
                $charges = $intent->charges->data;
                $transactionId = $user_id = $cart_id = $payment_form = $order_number = '';
                $amount = 0;
                if(count($charges)){
                    $transactionId = $charges[0]->balance_transaction;
                    $payment_form = $charges[0]->metadata->payment_form;
                    $amount = $charges[0]->amount / 100;
                    $user_id = $charges[0]->metadata->user_id;
                }

                if($payment_form == 'cart'){
                    $order_number = $charges[0]->metadata->order_number;
                    $cart_id = $charges[0]->metadata->cart_id ?? '';
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        $order->payment_status = 1;
                        $order->save();
                        $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                        if (!$payment_exists) {
                            $payment = new Payment();
                            $payment->date = date('Y-m-d');
                            $payment->order_id = $order->id;
                            $payment->transaction_id = $transactionId;
                            $payment->balance_transaction = $amount;
                            $payment->type = 'cart';
                            $payment->save();
    
                            // Auto accept order
                            $orderController = new OrderController();
                            $orderController->autoAcceptOrderIfOn($order->id);
    
                            // Remove cart
                            Cart::where('id', $cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                            CartAddon::where('cart_id', $cart_id)->delete();
                            CartCoupon::where('cart_id', $cart_id)->delete();
                            CartProduct::where('cart_id', $cart_id)->delete();
                            CartProductPrescription::where('cart_id', $cart_id)->delete();
    
                            // Send Notification
                            if (!empty($order->vendors)) {
                                foreach ($order->vendors as $vendor_value) {
                                    $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                                    $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                                    $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                                }
                            }
                            $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
                            $super_admin = User::where('is_superadmin', 1)->pluck('id');
                            $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);
                        }
    
                        // Send Email
                        //   $this->successMail();
                    }
                } elseif($payment_form == 'wallet'){
                    $request->request->add(['user_id' => $user_id, 'wallet_amount' => $amount, 'transaction_id' => $transactionId]);
                    $walletController = new WalletController();
                    $walletController->creditWallet($request);
                }
                elseif($payment_form == 'tip'){
                    $order_number = $charges[0]->metadata->order_number;
                    $request->request->add(['user_id' => $user_id, 'order_number' => $order_number, 'tip_amount' => $amount, 'transaction_id' => $transactionId]);
                    $orderController = new OrderController();
                    $orderController->tipAfterOrder($request);
                }
                elseif($payment_form == 'subscription'){
                    $subscription = $charges[0]->metadata->subscription_id;
                    $request->request->add(['user_id' => $user_id, 'payment_option_id' => 19, 'amount' => $amount, 'transaction_id' => $transactionId]);
                    $subscriptionController = new UserSubscriptionController();
                    $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription);
                }
                break;
            
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                // \Log::info($paymentIntent);

                $meta = $paymentIntent->metadata;
                // \Log::info($meta);
                $user_id = $payment_form = $order_number = '';
                // $amount = $paymentIntent->amount / 100;
                if($meta){
                    $payment_form = $meta->payment_form;
                    $user_id = $meta->user_id;
                }
                $user = User::find($user_id);

                if($payment_form == 'cart'){
                    $order_number = $meta->order_number;
                    $order = Order::where('order_number', $order_number)->first();
                    if($order){
                        $wallet_amount_used = $order->wallet_amount_used;
                        if($wallet_amount_used > 0){
                            $wallet = $user->wallet;
                            $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);
                        }

                        // $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                        // foreach($order_products as $order_prod){
                        //     OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                        // }
                        // OrderProduct::where('order_id', $order->id)->delete();
                        // OrderProductPrescription::where('order_id', $order->id)->delete();
                        // VendorOrderStatus::where('order_id', $order->id)->delete();
                        // OrderVendor::where('order_id', $order->id)->delete();
                        // OrderTax::where('order_id', $order->id)->delete();
                        // $order->delete();
                    }
                }
                break;
            
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
    }

    public function paymentWebViewStripeFPX(Request $request, $domain='')
    {
        // try{
            $auth_token = $request->auth_token;
            $user = User::where('auth_token', $auth_token)->first();
            Auth::login($user);
            $payment_form = $request->payment_form;
            $returnParams = 'amount='. $request->amount . '&payment_form=' . $payment_form;
            if($payment_form == 'cart'){
                $returnParams .= '&order='.$request->order_number;
            }
            elseif($payment_form == 'tip'){
                $returnParams .= '&order='.$request->order_number;
            }
            $payment_retrive_stripe_fpx_url = url('payment/webview/response/stripe_fpx' .'/?'. $returnParams);
            
            $request->request->add(['come_from' => 'app', 'payment_form' => $payment_form]);
            $data = $request->all();
            return view('frontend.payment_gatway.stripe_fpx_view')->with(['data' => $data, 'payment_retrive_stripe_fpx_url'=>$payment_retrive_stripe_fpx_url]);
        // }
        // catch(\Exception $ex){
        //     return redirect()->back()->with('errors', $ex->getMessage());
        // }
    }

    public function webViewResponseStripeFPX(Request $request)
    {
        if($request->has('payment_intent')){
            $url = 'payment/gateway/returnResponse?status=0&gateway=stripe_fpx&action='.$request->payment_form;
            if($request->has('redirect_status') && ($request->redirect_status == 'succeeded')){
                $url = 'payment/gateway/returnResponse?status=200&gateway=stripe_fpx&action='.$request->payment_form;
                if($request->payment_form == 'cart'){
                    $url = $url.'&order='.$request->order;
                }
            }
            return Redirect::to($url);
        }
    }
}
