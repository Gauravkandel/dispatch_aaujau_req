<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => []], function() {
Route::post('otp_test', 'Api\TaskController@smstest')->middleware('ConnectDbFromOrder');
Route::post('check-dispatcher-keys', 'Api\TaskController@checkDispatcherKeys')->middleware('ConnectDbFromOrder');
Route::post('get-delivery-fee', 'Api\TaskController@getDeliveryFee')->middleware('ConnectDbFromOrder');
Route::post('task/create', 'Api\TaskController@CreateTask')->middleware('ConnectDbFromOrder');
Route::post('get/agents', 'Api\AgentController@getAgents')->middleware('ConnectDbFromOrder');
Route::post('agent/check_slot', 'Api\AgentSlotController@getAgentsSlotByTags')->middleware('ConnectDbFromOrder');
Route::post('task/lims/create', 'Api\TaskController@CreateLimsTask')->middleware('ConnectDbFromOrder');
Route::post('agent/create', 'Api\DriverRegistrationController@storeAgent')->middleware('ConnectDbFromOrder');
Route::post('send-documents','Api\DriverRegistrationController@sendDocuments')->middleware('ConnectDbFromOrder');
Route::get('get-agent-tags', 'Api\TaskController@getAgentTags')->middleware('ConnectDbFromOrder');
Route::get('get-all-teams', 'Api\TaskController@getAllTeams')->middleware('ConnectDbFromOrder');
Route::post('update-create-vendor-order', 'Api\AuthController@updateCreateVendorOrder')->middleware('ConnectDbFromOrder');


Route::post('chat/sendNotificationToAgent',      'Api\ChatControllerOrderNotification@sendNotificationToAgent')->middleware('ConnectDbFromOrder');


Route::post('update-order-feedback','Api\TaskController@SaveFeedbackOnOrder')->name('SaveFeedbackOnOrder')->middleware('ConnectDbFromOrder');

Route::post('upload-image-for-task','Api\TaskController@uploadImageForTask')->name('uploadImageForTask')->middleware('ConnectDbFromOrder');

Route::get('/notification/tracking/{order_id}', 'Api\TaskController@notificationTrackingDetail')->middleware('ConnectDbFromOrder');

Route::post('shortCode', 'Api\ShortcodeController@validateCompany');

Route::get('importCustomer', 'Api\ImportThirdPartyUserController@importCustomer');

// routes for edit order
Route::post('edit-order/driver/notify', 'Api\TaskController@editOrderNotification')->middleware('ConnectDbFromOrder');

//route for reschedule order
Route::post('order/reschedule', 'Api\OrderController@rescheduleOrder')->middleware('ConnectDbFromOrder');

// route for cancel order request status
Route::post('cancel-order-request-status/driver/notify', 'Api\TaskController@cancelOrderRequestStatusNotification')->middleware('ConnectDbFromOrder');

Route::group(['middleware' => ['dbCheck', 'apiLocalization']], function() {
    Route::get('client/preferences', 'Api\ActivityController@clientPreferences');
    Route::get('cmscontent','Api\ActivityController@cmsData');
});


Route::group(['prefix' => 'auth'], function () {

	Route::group(['middleware' => ['dbCheck', 'AppAuth', 'apiLocalization']], function() {
        Route::get('logout', 'Api\AuthController@logout');
    });

    Route::group(['middleware' => ['dbCheck','apiLocalization']], function() {
        Route::post('new-send-documents','Api\DriverRegistrationController@sendDocuments');
    	Route::post('sendOtp', 'Api\AuthController@sendOtp');
        Route::post('login', 'Api\AuthController@login');        
        Route::post('signup', 'Api\AuthController@signup');
        Route::post('signup/sendOtp', 'Api\DriverRegistrationController@sendOtp');
        Route::post('signup/verifyOtp', 'Api\DriverRegistrationController@verifyOtp');
        //Route::get('cmscontent','Api\ActivityController@cmsData');
    });

});

Route::group(['middleware' => ['dbCheck', 'AppAuth','apiLocalization']], function() {
    Route::get('user', 'Api\AuthController@user');
    Route::post('agent/delete', 'Api\AuthController@deleteAgent');
    
    Route::get('taskList', 'Api\ActivityController@tasks');                    // api for task list
    Route::get('updateStatus', 'Api\ActivityController@updateDriverStatus');   // api for chnage driver status active ,in-active
    Route::post('updateTaskStatus', 'Api\TaskController@updateTaskStatus');    // api for chnage task status like start,cpmplate,faild
    Route::post('checkOTPRequried', 'Api\TaskController@checkOTPRequried');    // api for chnage task status like start,cpmplate,faild
    Route::post('task/accecpt/reject', 'Api\TaskController@TaskUpdateReject'); // api for accecpt task reject task
    Route::post('agent/logs', 'Api\ActivityController@agentLog');              // api for save agent logs
    Route::get('get/profile','Api\ActivityController@profile');                // api for get agent profile
    Route::post('update/profile','Api\ActivityController@updateProfile');       // api for updateprofile
    Route::get('task/history','Api\ActivityController@taskHistory');            // api for get task history
    Route::post('agentWallet/credit', 'Api\WalletController@creditAgentWallet');      // api for credit money into agent wallet
    Route::get('payment/options/{page}','Api\PaymentOptionController@getPaymentOptions'); // api for payment options
    Route::get('agent/transaction/details/{id}', 'Api\DriverTransactionController@transactionDetails');   // api for agent transactions
    Route::get('agent/bank/details', 'Api\AgentPayoutController@agentBankDetails'); // api for getting agent bank details
    Route::get('agent/payout/details', 'Api\AgentPayoutController@agentPayoutDetails'); // api for agent payout details
    Route::post('agent/payout/request/create/{id}', 'Api\AgentPayoutController@agentPayoutRequestCreate'); // api for creating agent payout request
    Route::post('chat/startChat',      'Api\ChatController@startChat');
    Route::post('chat/userAgentChatRoom',      'Api\ChatController@userAgentChatRoom');
    Route::post('chat/sendNotification',      'Api\ChatController@sendNotificationToUser');
    //Route::post('chat/userAgentChatRoom',      'Api\ChatController@startChat');
    
    // Order routes
    Route::post('order/cancel/request/create/{id}', 'Api\OrderController@createOrderCancelRequest'); // api for creating order cancel request by driver
    Route::get('order/cancel/reasons', 'Api\OrderController@getOrderCancelReasons'); // api for creating order cancel request by driver

    // Driver subscription
    Route::group(['prefix' => 'driver/subscription'], function () {
        Route::get('plans', 'Api\DriverSubscriptionController@getSubscriptionPlans');
        Route::get('selectPlan/{slug}', 'Api\DriverSubscriptionController@selectSubscriptionPlan');
        Route::post('purchase/{slug}', 'Api\DriverSubscriptionController@purchaseSubscriptionPlan');
        Route::post('cancel/{slug}', 'Api\DriverSubscriptionController@cancelSubscriptionPlan');
        Route::get('checkActivePlan/{slug}', 'Api\DriverSubscriptionController@checkActiveSubscriptionPlan');
    });

    // All Payment gateways
    Route::get('payment/{gateway}', 'Api\PaymentOptionController@postPayment');
});


Route::group(['middleware' => 'dbCheck','prefix' => 'public'], function() {
    Route::post('task/create', 'Api\TaskController@CreateTask');
    Route::get('task/currentstatus', 'Api\TaskController@currentstatus');
   
});


});
