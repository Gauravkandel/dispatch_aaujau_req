<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Exception;
use DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Traits\ApiResponser;

use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use App\Model\{Agent,AgentSlot,AgentSlotRoster,SlotDay};


class AgentSlotController extends Controller
{
    use ApiResponser;
    public $Blockedslots = 'rgb(119 142 72)';
    public $workingColor = '#43bee1';
    public $blockColor = 'rgb(155 90 90)';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $agent = Agent::where('id', $request->agent_id)->firstOrFail();
            if(!$agent){
                $this->error('Agent not fount!',405);
            }
          
            $dateNow = Carbon::now()->format('Y-m-d');
            $slotData = array();

            $block_time = explode('-', $request->blocktime);
            $start_date = date("Y-m-d H:i:s",strtotime($block_time[0]));
            $end_date   = date("Y-m-d H:i:s",strtotime($block_time[1]));
        
            $period   = CarbonPeriod::create($start_date, $end_date);
            $weekdays = $request->recurring == 1 ? $request->week_day  : [1,2,3,4,5,6,7]; 
          
            $slot = new AgentSlot();
            $slot->agent_id     = $agent->id;
            $slot->start_time   = $request->start_time;
            $slot->end_time     = $request->end_time;
            $slot->start_date   = $start_date;
            $slot->end_date     = $end_date;
            $slot->recurring    = $request->recurring;
            $slot->save();
            
            if(isset($slot->id)){
                foreach ($weekdays as $k => $day) {
                    $slotData['slot_id']    = $slot->id;
                    $slotData['day']        = $day;
                    SlotDay::insert($slotData); 
                }
                $AgentSlotData = [];
                // Iterate over the period
                foreach ($period as $key => $date) {
                    $dayNumber = $date->dayOfWeek+1; // get day number 
                    if(in_array($dayNumber, $weekdays)){
                        $AgentSlotData[$key]['slot_id']        = $slot->id;
                        $AgentSlotData[$key]['agent_id']       = $request->agent_id;
                        $AgentSlotData[$key]['start_time']     = $request->start_time;
                        $AgentSlotData[$key]['end_time']       = $request->end_time;
                        $AgentSlotData[$key]['schedule_date']  = $date->format('Y-m-d H:i:s');
                        $AgentSlotData[$key]['booking_type']   = $request->booking_type ?? 'working_hours' ;
                        $AgentSlotData[$key]['memo']           = $request->memo ?? __('Working Hours');
                    }
                }
            }
            AgentSlotRoster::insert($AgentSlotData);
            DB::commit(); //Commit transaction after all the operations
         
             return $this->success('', __('Slot saved successfully!'));
        } catch (Exception $e) {
           
            DB::rollBack();
            return response()->json(array('success' => false, 'message'=>'Something went wrong.'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorSlot  $vendorSlot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
     
        try {
            DB::beginTransaction();
            $AgentSlot = AgentSlot::where('id', $request->slot_id)->firstOrFail();
            if(!$AgentSlot){
                $this->error('Slot not fount!',405);
            }
          
            $dateNow = Carbon::now()->format('Y-m-d');
            $slotData = array();

            $block_time = explode('-', $request->blocktime);
            $start_date = date("Y-m-d H:i:s",strtotime($block_time[0]));
            $end_date   = date("Y-m-d H:i:s",strtotime($block_time[1]));
        
            $period   = CarbonPeriod::create($start_date, $end_date);
            $weekdays = $request->recurring == 1 ? $request->week_day  : [1,2,3,4,5,6,7]; 
            //for particular date
            if($request->recurring ==0){
                $seleted_date =Carbon::parse($request->edit_slot_date)->format('Y-m-d'); ;
                if( $seleted_date < $dateNow){
                    return response()->json(array('success' => false, 'message'=>__('Inveled date.')));
                }
                $slot_roster = AgentSlotRoster::where(['slot_id'=>$AgentSlot->id,'agent_id'=>$request->agent_id])->whereDate('schedule_date', $seleted_date)->first() ?? new AgentSlotRoster();
                $slot_roster->slot_id        = $AgentSlot->id;
                $slot_roster->agent_id       = $request->agent_id;
                $slot_roster->start_time     = $request->start_time;
                $slot_roster->end_time       = $request->end_time;
                $slot_roster->schedule_date  = $seleted_date;
                $slot_roster->booking_type   = $request->booking_type ?? 'working_hours' ;
                $slot_roster->memo           = $request->memo ?? __('Working Hours');
                $slot_roster->save();
               
                DB::commit(); //Commit transaction after all the operations
         
                return $this->success('', __('Slot saved successfully!'));
            }
            $AgentSlot->start_time   = $request->start_time;
            $AgentSlot->end_time     = $request->end_time;
            $AgentSlot->start_date   = $start_date;
            $AgentSlot->end_date     = $end_date;
            $AgentSlot->recurring    = $request->recurring == 'true' ? 1 : 0;
            $AgentSlot->save();
            
            if(isset($AgentSlot->id)){
                $SlotDayID = [];
                foreach ($weekdays as $k => $day) {
                    $slotData = SlotDay::where(['slot_id'=>$AgentSlot->id,'day'=>$day])->first() ?? new SlotDay();
                
                    $slotData->slot_id = $AgentSlot->id;
                    $slotData->day = $day;
                    $slotData->save();
                    $SlotDayID[]=$slotData->id;
                }
                SlotDay::where('slot_id',$AgentSlot->id)->whereNotIn('id',$SlotDayID)->delete();
                $AgentSlotDataId= [];
                // Iterate over the period
                foreach ($period as $key => $date) {
                    $dayNumber = $date->dayOfWeek+1; // get day number 
                    if(in_array($dayNumber, $weekdays)){
                        $slot_roster = AgentSlotRoster::where(['slot_id'=>$AgentSlot->id,'agent_id'=>$request->agent_id])->whereDate('schedule_date', $date->format('Y-m-d'))->first() ?? new AgentSlotRoster();
                        $slot_roster->slot_id        = $AgentSlot->id;
                        $slot_roster->agent_id       = $request->agent_id;
                        $slot_roster->start_time     = $request->start_time;
                        $slot_roster->end_time       = $request->end_time;
                        $slot_roster->schedule_date  = $date->format('Y-m-d H:i:s');
                        $slot_roster->booking_type   = $request->booking_type ?? 'working_hours' ;
                        $slot_roster->memo           = $request->memo ?? __('Working Hours');
                        $slot_roster->save();
                        $AgentSlotDataId[] = $slot_roster->id;
                    }
                }
            } 
            AgentSlotRoster::where('slot_id',$AgentSlot->id)->whereNotIn('id',$AgentSlotDataId)->delete();
            DB::commit(); //Commit transaction after all the operations
         
             return $this->success('', __('Slot saved successfully!'));
        } catch (Exception $e) {
           //pr($e->getMessage());
            DB::rollBack();
            return response()->json(array('success' => false, 'message'=>__('Something went wrong.')));
        }

    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorSlot  $vendorSlot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      
        // try {
        //     DB::beginTransaction();
            $dateNow = Carbon::now()->format('Y-m-d');
            
            if($request->delete_type=='single'){
                $slot_date = $request->has('slot_date') ? $request->slot_date :  Carbon::now(); ;
                $seleted_date = Carbon::parse($slot_date)->format('Y-m-d');
                if( $seleted_date < $dateNow){
                    return response()->json(array('success' => false, 'message'=>__("You can't delete past date.")));
                }
                AgentSlotRoster::where(['slot_id'=>$request->slot_id,'agent_id'=>$request->agent_id])->whereDate('schedule_date', $seleted_date)->delete();
                DB::commit(); //Commit transaction after all the operations
                return $this->success('', __('Slot deleted successfully!'));
            }

            $weekdays = $request->week_day;
            $block_time = explode('-', $request->blocktime);
            $start_date = date("Y-m-d H:i:s",strtotime($block_time[0]));
            $end_date   = date("Y-m-d H:i:s",strtotime($block_time[1]));
            $period     = CarbonPeriod::create($start_date, $end_date);
            //pr($request->all());
            foreach ($period as $key => $date) {
             
                $dayNumber = $date->dayOfWeek+1; // get day number //in_array($dayNumber, $weekdays) &&
                if( (  strtotime($dateNow) <= strtotime($date->format('Y-m-d')))  ){
                    AgentSlotRoster::where(['slot_id'=>$request->slot_id,'agent_id'=>$request->agent_id])->whereDate('schedule_date', $date->format('Y-m-d'))->delete();
                }
            }
            // foreach ($weekdays as $k => $day) {
            //     SlotDay::where(['slot_id'=>$request->slot_id,'day'=>$day])->delete();
            // }
            
            
            DB::commit(); //Commit transaction after all the operations
            return $this->success('', __('Slot deleted successfully!'));
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return response()->json(array('success' => false, 'message'=>'Something went wrong.'));
        // }
    }

    public function returnJson(Request $request, $domain = '', $id)
    {
        
        $Agent = Agent::findOrFail($id);
        $date = $day = array();

        if($request->has('start')){
            $start = explode('T', $request->start);
            $end  = explode('T', $request->end);

            $startDate = date('Y-m-d', strtotime($start[0])); 
            $endDate = date('Y-m-d', strtotime($end[0]));

            $datetime1 = new \DateTime($startDate);
            $datetime2 = new \DateTime($endDate);

            $interval = $datetime2->diff($datetime1);
            $days = $interval->format('%a');

            $date[] = $startDate;
            $day[] = 1;

            for ($i = 1; $i < $days; $i++) {
                $date[] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($startDate)));
                $day[] = $i + 1;
            }
        }else{
            $startDate = '';
            $endDate ='';
        }
        $booking_type = $request->has('eventType') ? $request->eventType : 'working_hours';
        $lst = count($date) - 1;
        // $slot = AgentSlot::select('agent_slots.*', 'slot_days.id as slot_day_id', 'slot_days.slot_id', 'slot_days.day')->join('slot_days', 'slot_days.slot_id', 'agent_slots.id')->where('agent_id', $id)->orderBy('slot_days.day', 'asc')->get();
     
        $AgentRoster = AgentSlotRoster::with('agentSlot','days')->where('agent_id',$Agent->id)->whereBetween('schedule_date', [$startDate, $endDate])->orderBy('schedule_date','asc')->where('booking_type',$booking_type)->get();

        $showData = array();
        $count = 0;
        
            if($AgentRoster){
                foreach ($AgentRoster as $k => $v) {
                    $order_url = '';
                    $days= $v->days->pluck('day');
                    $a_date = date('Y-m-d', strtotime($v->schedule_date));
                    $title = $v->memo ? $v->memo :'';
                    $color = $this->workingColor;
                    if($v->booking_type == 'blocked'){
                        $color = $this->blockColor;
                    }else if($v->booking_type == 'new_booking'){
                        $color = $this->Blockedslots;
                        $order_url = route('tasks.edit', $v->order_id);
                    }
                    
                    
                    $showData[$count]['title'] = trim($title);
                    $showData[$count]['start'] = $a_date.'T'.$v->start_time;
                    $showData[$count]['end'] = $a_date.'T'.$v->end_time;
                    $showData[$count]['start_time'] = $v->start_time;
                    $showData[$count]['end_time'] = $v->end_time;
                    $showData[$count]['color'] = $color;
                    $showData[$count]['type'] = 'date';
                    $showData[$count]['roster_id'] = $v->id;
                    $showData[$count]['slot_id'] = $v->slot_id;
                    $showData[$count]['schedule_date'] = $v->schedule_date;
                    $showData[$count]['memo'] = $v->memo;
                    $showData[$count]['booking_type'] = $v->booking_type;
                    $showData[$count]['recurring'] = $v->agentSlot ? $v->agentSlot->recurring : 0;
                    $showData[$count]['start_date'] = $v->agentSlot ? $v->agentSlot->start_date : 0;
                    $showData[$count]['end_date'] = $v->agentSlot ? $v->agentSlot->end_date : 0;
                    $showData[$count]['agent_id'] = $v->agent_id;
                    $showData[$count]['days'] = $days;
                    $showData[$count]['order_url'] = $order_url;
                    $count++;
                    
                }
            }

        
        echo $json  = json_encode($showData);
    }

   
   

}
