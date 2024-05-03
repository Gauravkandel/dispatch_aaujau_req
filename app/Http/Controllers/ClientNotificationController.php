<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\NotificationType;
use App\Model\NotificationEvent;
use App\Model\ClientNotification;
use App\Model\Roster;
use App\Model\ClientPreference;
use Carbon\Carbon;
use App\Jobs\SendPushNotifications;
use Illuminate\Support\Facades\Auth;

class ClientNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notification_types = NotificationType::with('notification_events')->get();
        $client_notifications = ClientNotification::where('client_id', 1)->get();
        $client_preference = ClientPreference::select('customer_notification_per_distance','custom_mode')->where('client_id', Auth::user()->code)->get()->first();
        $customMode = json_decode($client_preference->custom_mode);
        $showCustomerNotification = 0;
        if(!empty($customMode) && $customMode->is_hide_customer_notification == 1){
            $showCustomerNotification = 1;
        }
        return view('notifications')->with([
            'client_notifications' => $client_notifications,
            'notification_types'   => $notification_types,
            'client_preference' => json_decode($client_preference->customer_notification_per_distance),
            'showCustomerNotification' => $showCustomerNotification
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Validation method for clients Update
     */
    protected function updateClientEventValidator(array $data)
    {
        return Validator::make($data, [
            'notification_event_id' => ['required'],
            'current_value' => ['required'],
            'notification_type' => ['required']
        ]);
    }

    public function updateClientNotificationEvent(Request $request, $domain = '')
    {
        $validator = $this->updateClientEventValidator($request->all())->validate();

        switch ($request->notification_type) {
            case 'sms':
                $update = ['request_recieved_sms' => $request->current_value];
                break;
            case 'email':
                $update = ['request_received_email' => $request->current_value];
                break;
            case 'recipient_sms':
                $update = ['recipient_request_recieved_sms' => $request->current_value];
                break;
            case 'recipient_email':
                $update = ['recipient_request_received_email' => $request->current_value];
                break;
            case 'webhook':
                $update = ['request_recieved_webhook' => $request->current_value];
                break;
            default:
                # code...
                break;
        }

        ClientNotification::updateOrCreate([
            'client_id' => Auth::user()->code,
            'notification_event_id' => $request->notification_event_id
        ], $update);

        return response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully',
            'data' => ''
        ]);
    }

    protected function updateWebhookValidator(array $data)
    {
        return Validator::make($data, [
            'notification_event_id' => ['required'],
            'webhook_url' => ['required']
        ]);
    }

    public function setWebhookUrl(Request $request)
    {
        $validator = $this->updateWebhookValidator($request->all())->validate();

        $update = [
            'webhook_url' => $request->webhook_url
        ];

        ClientNotification::updateOrCreate([
            'client_id' => 1,
            'notification_event_id' => $request->notification_event_id
        ], $update);

        return redirect()->back();
    }

    public function setmessage(Request $request)
    {
        $message = NotificationEvent::find($request->notification_event_id);
        $message->message = $request->message;
        $message->save();

        return redirect()->back();
    }


    //Push notification to drivers

    public function SendPushNotification()
    {
        $recipients = [];
        $date =  Carbon::now()->toDateTimeString();
        $get = Roster::where('notification_time', '<=', $date)->with('agent')->get();
        
        foreach ($get as $item) {
            array_push($recipients, $item->agent->device_token);
        }
       
        if (isset($recipients)) {
            fcm()
            ->to($recipients) // $recipients must an array
            ->priority('high')
            ->timeToLive(0)
            ->data([
                'title' => 'Test FCM',
                'body' => 'This is a test of FCM',
            ])
            ->notification([
                'title' => 'Test FCM',
                'body' => 'This is a test of FCM',
            ])
            ->send();
        }
    }
}
