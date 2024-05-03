<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Model\{Agent, AgentSlot,AgentSlotRoster};

trait AgentSlotTrait{

    //------------------------------Function created by surendra singh--------------------------//
    public static function SlotBooking($data)
    {
        try {
            $end_date =  $start_date = date("Y-m-d H:i:s",strtotime( $data['schedule_time']));
            $start_time = date("H:i:s",strtotime( $data['schedule_time']));
            $end_time   = date("H:i:s", strtotime($data['schedule_time']."+".$data['service_time']." minutes"));
        
            $AgentSlot = new AgentSlot();
        
            $AgentSlot->agent_id     = $data['agent'];
            $AgentSlot->start_time   = $start_time;
            $AgentSlot->end_time     = $end_time;
            $AgentSlot->start_date   = $start_date;
            $AgentSlot->end_date     = $end_date;
            $AgentSlot->recurring    = 0;
            $AgentSlot->save();
            \Log::info('AgentSlot create');
            \Log::info($data['agent']);
            if($AgentSlot){
                $slot_roster  =   new AgentSlotRoster();
                $slot_roster->slot_id        =  $AgentSlot->id;
                $slot_roster->agent_id       =  $data['agent'];
                $slot_roster->start_time     =  $start_time;
                $slot_roster->end_time       =  $end_time;
                $slot_roster->schedule_date  =  $start_date;
                $slot_roster->booking_type   =  $data['booking_type'] ?? 'new_booking' ;
                $slot_roster->memo           =  $data['memo'] ??'';
                $slot_roster->order_id       =  $data['order_id'] ??'';
                $slot_roster->order_number   =  $data['order_number'] ??'';
                $slot_roster->save();
            }
            return 1;
        }catch (\Exception $e) {
            \Log::info($e->getMessage());
            return 2;
        }
    }
    //-------------------------------------------------------------------------------------------//
    

}
