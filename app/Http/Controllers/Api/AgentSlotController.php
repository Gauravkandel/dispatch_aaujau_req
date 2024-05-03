<?php

namespace App\Http\Controllers\Api;

use DB;
use Config;
use Validator;
use Carbon\Carbon;
use App\Model\Roster;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GClient;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Agent, AgentSlot,AgentSlotRoster,AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof, Timezone, User, DriverGeo, Geo, TagsForAgent};

use App\Http\Controllers\Api\AgentController;

class AgentSlotController extends BaseController
{
    public function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}
    public function errorResponse($message = null, $code, $data = null)
	{
		return response()->json([
			'status' => 'Error',
			'message' => $message,
			'data' => $data
		], $code);
	}
    /**   get agent according to lat long  */
    function getAgentsSlotByTags(Request $request){
       
        //pr($request->all());
         try {
            $preference =  ClientPreference::first();
            if($preference->is_driver_slot != 1){
                $response = [
                    'agents' =>  [],
                    'slots' =>  [],
                ];
                return response()->json([
                    'data' => $response,
                    'status' => 200,
                    'message' => __('success! slot Not active!')
                ], 200);
               
            }
            $validator = Validator::make(request()->all(), [
                'latitude'  => 'required',
                'longitude' => 'required',
                'tags'      => 'required',
                'schedule_date' => 'required',
            ]);

            if($validator->fails()){
                \Log::info($validator->messages());
                $response = [
                    'agents' =>  [],
                    'slots' =>  [],
                ];
                return response()->json([
                    'data' => $response,
                    'status' => 200,
                    'message' => __('success! slot Not active!')
                ], 200);
            }
            $agentController = new AgentController();
            $geoid = $agentController->findLocalityByLatLng($request->latitude, $request->longitude);
            $geoagents_ids = DriverGeo::where('geo_id', $geoid)->pluck('driver_id');
          //  pr($geoagents_ids);
            $tagId = '';
            if(!empty($request->tags)){
                $tag = TagsForAgent::where('name', $request->tags)->get()->first();
                if(!empty($tag)){
                    $tagId = $tag->id;
                }else{
                    return response()->json([
                        'data' => [],
                        'status' => 200,
                        'message' => __('Agents not found.')
                    ], 200);
                }

            }
            $timezoneset = 'Asia/Kolkata';
          
            $tagId = '';
            $myDate = date('Y-m-d',strtotime( $request->schedule_date));
           
            $geoagents = Agent::with(['slots' => function($q) use($myDate){
                $q->whereDate('schedule_date', $myDate);
                $q->where('booking_type', 'working_hours');
            }]);

            if(!empty($tagId)){
                $geoagents->whereHas('tags', function($q) use($tagId){
                    $q->where('tag_id', $tagId);
                });
            }
         
            if($request->schedule_date){
                $geoagents->whereHas('slots', function($q) use($myDate){
                    $q->whereDate('schedule_date', $myDate);
                });
            }
            

            $geoagents = $geoagents->whereIn('id', $geoagents_ids)->where([ "is_approved" => 1])->orderBy('id', 'DESC')->get();
            $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
            $agents=[];
            $commonSlot=[];
            foreach( $geoagents as $agent){
                $agent->image_url =  isset($agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($agent->profile_picture) : Phumbor::url(URL::to('/asset/images/no-image.png'));
              
                $slotss = [];
                $mergeArray = [];
                $Duration  = $request->service_time ?? 60;
                foreach ($agent->slots as $slott) {
                
                        $new_slot = $this->SplitTime($myDate, $slott->start_time, $slott->end_time, $Duration,$timezoneset, $delayMin = 0,$request->slot_start_time);
                      
                        if (!in_array($new_slot, $slotss) && (count( $new_slot) > 0) ) {
                            $slotss[] = $new_slot;
                            //$mergeArray=  array_merge($slotss,$new_slot);
                        }
                    
                }
                $arr = array();
                $count = count($slotss);
                for ($i=0;$i<$count;$i++) {
                    $arr = array_merge($arr, $slotss[$i]);
                }
                $viewSlot = [];
                if (isset($arr)) {
                    foreach ($arr as $k=> $slt) {
                        $sl = explode(' - ', $slt);
                        $viewSlot[$k]['name'] = date('h:i A', strtotime($sl[0])).' - '.date('h:i A', strtotime($sl[1]));
                        $viewSlot[$k]['value'] = $slt;
                        $viewSlot[$k]['agent_id'] = $agent->id;

                        $commonSlot[$agent->id][$k]['name'] = date('h:i A', strtotime($sl[0])).' - '.date('h:i A', strtotime($sl[1]));
                        $commonSlot[$agent->id][$k]['value'] = $slt;
                        $commonSlot[$agent->id][$k]['agent_id'] = $agent->id;
                    }
                }
                // pr($viewSlot);
                $agent->slotCount = count( $viewSlot);
                $agent->slotings = $viewSlot;
               
            if(count( $viewSlot) >0){
                    $agents[] = $agent;
                }
                
            }

           $commonAllSlot=[];
           
            foreach( $commonSlot as $key=>$commonSlots){
                $commonAllSlot = array_merge($commonAllSlot, $commonSlots);
            }

            // set all vendor slot  in one array
            $result = array();
            foreach( $commonAllSlot as $key=>$AllSlot){
                $sl = explode(' - ', $AllSlot['value']);

                $checkSlotAvailable = AgentSlotRoster::where(['agent_id'=>$AllSlot['agent_id']])
                                                    ->whereIn('booking_type',['blocked','new_booking'])
                                                    ->whereDate('schedule_date', $myDate)
                                                    ->where(function ($query) use ($sl ){
                                                        $query->where('start_time', '<=', $sl[1])
                                                            ->where('end_time', '>=', $sl[0]);
                                                    })->first();
               
                if(!$checkSlotAvailable)   {
                    $result[$AllSlot['value']]['name'] = $AllSlot['name'];
                    $result[$AllSlot['value']]['value'] = $AllSlot['value'];
                    $result[$AllSlot['value']]['agent_id'][] = $AllSlot['agent_id']; //isset($result[$AllSlot['value']]['agent_id'] )? $result[$AllSlot['value']]['agent_id'].','. $AllSlot['agent_id'] :$AllSlot['agent_id'] ;

                }                             
            }
            
            $response = [
                'agents' =>  $agents,
                'slots' =>  $result,
            ];
            return response()->json([
                'data' => $response,
                'status' => 200,
                'message' => __('success')
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }
    public function SplitTime($myDate, $StartTime, $EndTime, $Duration="60", $timezoneset,$delayMin = 0,$slot_start_time)
    {
        $Duration = (($Duration==0)?'60':$Duration);

        $cr = Carbon::now()->addMinutes($delayMin);
        $date = Carbon::parse($cr, 'UTC');
        $now = $date->setTimezone($timezoneset);

       
        $nowT = strtotime($now);
        
        $nowA = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$StartTime);
        
        $nowS = Carbon::createFromFormat('Y-m-d H:i:s', $nowA)->timestamp;
        $nowE = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$EndTime)->timestamp;
        if ($nowT > $nowE) {
            return [];
        } elseif ($nowT>$nowS) {
           
            $StartTime = date('H:i', strtotime($now. ' +10 minutes'));
            if( $StartTime >= $slot_start_time){
                $StartTime = $slot_start_time;
            }
        } else {
            $StartTime = date('H:i', strtotime($nowA));
        }
        $ReturnArray = array();
        $StartTime = strtotime($StartTime); //Get Timestamp
        $EndTime = strtotime($EndTime); //Get Timestamp
        $AddMins = $Duration * 60;
        $endtm = 0;
    
        while ($StartTime <= $EndTime) {
            $endtm = $StartTime + $AddMins;
            if ($endtm>$EndTime) {
                $endtm = $EndTime;
            }
            $ReturnArray[] = date("G:i", $StartTime).' - '.date("G:i", $endtm);
            $StartTime += $AddMins;
            $endtm = 0;
        }
        return $ReturnArray;
    }

      /**   get agent according to lat long  */
   function checkAgentsSlotavailablty(Request $request){
       
    //pr($request->all());
        try {

            $validator = Validator::make(request()->all(), [
                'agent_id' => 'required',
                'schedule_date' => 'required',
                'schedule_time' => 'required',
            ]);

            if($validator->fails()){
                \Log::info($validator->messages());
                $response = [
                    'agents' =>  [],
                    'slots' =>  [],
                ];
                return response()->json([
                    'data' => $response,
                    'status' => 200,
                    'message' => __('success! slot Not active!')
                ], 200);
            }
            $block_time = explode('-', $request->blocktime);
            $start_time = date("H:i:s",strtotime( $block_time[0]));
            $end_time = date("H:i:s",strtotime($block_time[1]));

            $timezoneset = 'Asia/Kolkata';
        
            $tagId = '';
            $myDate = date('Y-m-d',strtotime( $request->schedule_date));
        
            $AgentSlotRoster = AgentSlotRoster::whereDate('schedule_date', $myDate)->where(['agent_id' =>$request->agent_id])
                                            ->where(function ($query) use ($start_time , $end_time ){
                                                $query->where('start_date_time', '<=', $end_time)
                                                    ->where('end_date_time', '>=', $start_time);
                                            })
                                            ->where(function ($query) use ($start_time , $end_time ){
                                                $query->where('booking_type', '!=', 'blocked')
                                                    ->whereOr('booking_type', '!=', 'new_booking');
                                            })->first();

            
        
            return response()->json([
                'data' => $AgentSlotRoster,
                'status' => 200,
                'message' => __('success'),
            ], 200);

        }catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }
}
