<?php

namespace App\Http\Controllers\Api;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Client};
use App\Traits\ChatTrait;
use Illuminate\Support\Facades\Http;

class ChatController extends BaseController
{
    use ApiResponser;
    use ChatTrait;
    public $client_data;
    public function __construct()
    {
        
        $this->middleware(function ($request, $next) {
            $this->id = Auth::user()->id;
            $data = Client::first();
            $this->client_data =  $data;
            if ($data->socket_url == null) {
                abort(404);
            }
    
            return $next($request);
        });
    }    
    /**
     * getChatRoom
     *
     * @param  mixed $vendor_id
     * @param  mixed $type
     * @param  mixed $sub_domain
     * @return void
     */
    public function getChatRoom($vendor_id,$type,$sub_domain){
        try {
            //code...
            $clientData = $this->client_data;
            $server_name = $sub_domain;
            $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByVendor', [
                'vendor_id' => $vendor_id, 
                'sub_domain' =>$server_name,
                'type'=>$type,
                'db_name'=>$clientData->database_name,
                'client_id'=>$clientData->id
            ]);
            $statusCode = $response->getStatusCode();
            if($statusCode == 200) {
                $roomData = $response['roomData'];
                return ['status' => true, 'roomData' => $roomData , 'message' => __('Room list !!!')];
            } else {

                return ['status' => false, 'message' => __('Something went wrong!!!')];
            }

        } catch (\Throwable $th) {
            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
        

    }
    
    /**
     * getChatRoomForAgent
     *
     * @param  mixed $agent_id
     * @param  mixed $type
     * @param  mixed $sub_domain
     * @return void
     */
    public function getChatRoomForAgent($agent_id,$type,$sub_domain){
        try {
            //code...
            $clientData = $this->client_data;
            $server_name = $sub_domain;
            $response =   Http::post($clientData->socket_url.'/api/room/fetchRoomByUserAgent', [
                'order_user_id' => $agent_id, 
                'sub_domain' =>$server_name,
                'agent_id' =>$agent_id,
                'type'=>$type,
                'agent_db'=>$clientData->database_name,
                'db_name'=>'',
                'client_id'=>$clientData->id
            ]);
            
            $statusCode = $response->getStatusCode();
            if($statusCode == 200) {
                $roomData = $response['roomData'];
            
                return ['status' => true, 'roomData' => $roomData , 'message' => __('Room list !!!')];
            } else {

                return ['status' => false, 'message' => __('Something went wrong!!!')];
            }
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => __('Something went wrong!!!')];
        }
    

    }    
    /**
     * vendorUserChatRoom
     *
     * @param  mixed $request
     * @return void
     */
    public function vendorUserChatRoom(Request $request){
        try {
            $user = Auth::user();
            $data = $request->all();
            $sub_domain = $data['sub_domain'];
            $vendor_id = UserVendor::where('user_id',$user->id)->pluck('vendor_id');
            $this->client_data['vendor_id'] = $vendor_id;
            $roomData = $this->getChatRoom($vendor_id,'vendor_to_user',$sub_domain);
            if($roomData['status']){
                $chatroom = $roomData['roomData'];
            } else {
                $chatroom = [];
            }
            return response()->json([ 'chatrooms'=>$chatroom , 'status' => true, 'message' => __('list fetched!!!')]);
        } catch (\Throwable $th) {
            return response()->json([ 'chatrooms'=>[] , 'status' => true, 'message' => __('list fetched!!!')]);
        }

    }


    public function userVendorChatRoom(Request $request){
        try {
            $user = Auth::user();
            $data = $request->all();
            $sub_domain = $data['sub_domain'];
            $roomData = $this->getChatRoomForUser($user->id,'vendor_to_user',$sub_domain);
            if($roomData['status']){
                $chatroom = $roomData['roomData'];
            } else {
                $chatroom = [];
            }
    
        
            return response()->json([ 'chatrooms'=>$chatroom , 'status' => true, 'message' => __('list fetched!!!')]);
        } catch (\Throwable $th) {
            return response()->json([ 'chatrooms'=>[] , 'status' => true, 'message' => __('list fetched!!!')]);
        }
       

    }

    /**
     * start Chat.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function startChat(Request $request)
    {
        try {
            //code...
        
            $data = $request->all();
            
            $vendor_id = $data['vendor_id'];
            $vendor_order_id = $data['vendor_order_id'];
            $order_id = $data['order_id'];
            $order_number = $data['order_number'];
        
            $langId = 1;
            $server_name = $_SERVER['SERVER_NAME'];
        
            
                $socket_url = $this->client_data->socket_url;
                $room_id = $order_number;
                $room_name = 'OrderNo-'.$order_number.'-orderId-'.$order_id.'-oderVendor-'.$vendor_id.'-agentId-'.$data['agent_id'];
                $order_vendor_id = $vendor_order_id;
                $orderby_user_id =  $data['order_user_id'];
                //$response = $client->request('Post', 'https://chat.royoorders.com/api/room', ['body' => [
                $response =   Http::post($socket_url.'/api/room/createRoom', [
                    'room_id' => $room_id, 
                    'room_name' => $room_name,
                    'order_vendor_id'=>$order_vendor_id,
                    'order_id'=>$order_id,
                    'agent_db'=>$this->client_data->database_name,
                    'vendor_id'=>$vendor_id,
                    'sub_domain' =>$data['sub_domain'],
                    'vendor_user_id' =>$data['user_id'],
                    'order_user_id' =>$orderby_user_id,
                    'type'=>$data['type'],
                    'agent_id'=>$data['agent_id'],
                    'db_name'=>$data['db_name'],
                    'client_id'=>$data['client_id']
                ]);
                $statusCode = $response->getStatusCode();
                if($statusCode == 200) {
                    $roomData = $response['roomData'];
                    return response()->json(['status' => true, 'roomData' => $roomData , 'message' => __('Room created successfully !!!')]);
                } else {

                    return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
                }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => __('Something went wrong!!!')]);
        }
        
        

    }
    
    /**
     * fetchOrderDetail
     *
     * @param  mixed $request
     * @return void
     */
    public function fetchOrderDetail(Request $request){
        try {
            $orderData = $this->OrderVendorDetail($request);
            return response()->json(['status' => true, 'orderData' => $orderData , 'message' => __('Data fetched !!!')]);
            
            //code...
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'orderData' => [] , 'message' => __('No Data found !!!')]);
        }
            
    }

    
    /**
     * userAgentChatRoom
     *
     * @param  mixed $request
     * @return void
     */
    public function userAgentChatRoom(Request $request){
        try {
            $user = Auth::user();
            $data = $request->all();
            $sub_domain = $data['sub_domain'];
            $agent_id = @$data['agent_id'];
            $roomData = $this->getChatRoomForAgent($user->id,'agent_to_user',$sub_domain);
            if($roomData['status']){
                $chatroom = $roomData['roomData'];
            } else {
                $chatroom = [];
            }
            return response()->json([ 'chatrooms'=>$chatroom , 'status' => true, 'message' => __('list fetched!!!')]);
        } catch (\Throwable $th) {
            return response()->json([ 'chatrooms'=>[] , 'status' => true, 'message' => __('list fetched!!!')]);
        }

    }
    
    /**
     * sendNotificationToUser
     *
     * @param  mixed $request
     * @return void
     */
    public function sendNotificationToUser(Request $request){
        try {
            $notiFY = $this->sendNotificationToOrder($request);
            return response()->json([ 'notiFY'=>$notiFY , 'status' => true, 'message' => __('sent!!!')]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found !!!')]);
        }

    }
}
