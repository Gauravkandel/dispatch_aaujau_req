<?php

namespace App\Http\Controllers\Godpanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Model\{ChatSocket,Client};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\BaseController;
use App\Http\Requests\{AddSocketRequest,EditSocketRequest};
use Session;
use DB;
use Log;

class chatSocketController extends Controller
{

    public function index()
    {
        
    }


    //...........................................For Plan page----------------------------//
    public function chatsocket(Request $request)
    {
        $ChatSocket = ChatSocket::all();        
        return view('godpanel/ChatSocket/ChatSocket')->with(['ChatSockets'=>$ChatSocket]);
    }


    public function chatsocketSave(AddSocketRequest $request)
    {
        
        try {
            DB::beginTransaction(); //Initiate transaction
                $data = $request->all();
                $data['status'] = ($data == true) ? 1 : 2;
                ChatSocket::create($data);
            DB::commit(); //Commit transaction after all the operations
            return redirect()->route('chatsocket')->with('success', 'Plan has been added successfully.');
            //code...
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('chatsocket')->with('error', 'Something went wrong , Please try later.');
        }
        
    }


    public function editchatsocket(Request $request,$id)
    {
       
        $chatSocket = ChatSocket::find($id);
        $returnHTML = view('godpanel.ChatSocket.edit-chatsocket')->with(['chatSocket'=>$chatSocket])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    public function upDateSocket(EditSocketRequest $request ,$id)
    {

       
        try {
            DB::beginTransaction(); //Initiate transaction
            $data = $request->all();
            $data2['title'] = $data['title'];
            $data2['domain_url'] = $data['domain_url'];
            $data2['status'] = !empty($data['status'])?(($data['status'] == "on")?1:2):2;
            ChatSocket::where('id', $id)->update($data2);
            DB::commit(); //Commit transaction after all the operations
           return redirect()->route('chatsocket')->with('success', 'Socket url has been updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('chatsocket')->with('error', 'Something went wrong , Please try later.');
        }
    }

    
    public function upDateSocketStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction(); //Initiate transaction
            $data = $request->all();
            $data2['status'] = $data['status'];
            ChatSocket::where('id', $id)->update($data2);
            DB::commit(); //Commit transaction after all the operations
            return response()->json(array('success' => true, 'message'=>'Socket url status has been updated.'));

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(array('success' => false, 'message'=>'Something went wrong.'));

        }
        
    }
    public function deleteSocketUrl(Request $request, $id)
    {
        try {
            DB::beginTransaction(); //Initiate transaction
            $data = $request->all();
            ChatSocket::where('id', $id)->delete();
            DB::commit(); //Commit transaction after all the operations
            return response()->json(array('success' => true, 'message'=>'Socket url status has been deleted.'));

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(array('success' => false, 'message'=>'Something went wrong.'));

        }
        
    }

}
