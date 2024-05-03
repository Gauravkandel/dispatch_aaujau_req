<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Traits\agentEarningManager;
use App\Model\{Agent, AgentPayment, AgentPayout, Order, Task, Transaction};

class DriverTransactionController extends BaseController
{
    public function transactionDetails(Request $request, $id)
    {
        $data = [];
        $agent = Agent::where('id', $id)->first();
        //$data['wallet_balance'] = $agent->balanceFloat;

        $cash  = 0;
        $order = 0;
        $driver_cost = 0;
        $credit = 0;
        $debit = 0;
        $tasks = [];
        $payments = [];
        $final_balance = 0.00;
        $totalCashCollected = 0;

        if ($agent) {
            $wallet_balance = 0;
            if($agent->wallet){
                $wallet_balance = $agent->balanceFloat;
            }
            //-----------------------------function calculation modified by surendra singh-------------------//
            $page = $request->has('page') ? $request->page : 1;
            $limit = $request->has('limit') ? $request->limit : 30;
            $cash  = $agent->order->where('status', 'completed')->sum('cash_to_be_collected');
            $driver_cost  = $agent->order->where('status', 'completed')->sum('driver_cost');
            //$order_cost = $agent->order->where('status', 'completed')->sum('order_cost');
            $order_cost = $driver_cost;
            
            $payout = AgentPayout::where(['agent_id'=>$agent->id, 'status'=> 1])->sum('amount');
            $pendingpayout = AgentPayout::where(['agent_id'=>$agent->id, 'status'=> 0])->sum('amount');
            
            $balance = agentEarningManager::getAgentEarning($agent->id, 1);
            $final_balance = number_format($balance, 2, '.', '');
            //-----------------------------------------------------------------------------------------------//
            $payments = AgentPayment::select(DB::raw('id, "payment" as transaction_type, NULL as order_id, NULL as dependent_task_id, NULL as task_type_id, NULL as location_id, NULL as appointment_duration, NULL as task_status, NULL as allocation_type, NULL as amount, NULL as type, NULL as meta, dr, cr, created_at'))
            ->where("driver_id", $id);

            $wallet_transactions = Transaction::select(DB::raw('id, "wallet" as transaction_type, NULL as order_id, NULL as dependent_task_id, NULL as task_type_id, NULL as location_id, NULL as appointment_duration, NULL as task_status, NULL as allocation_type, amount, type, meta, NULL as dr, NULL as cr, created_at'))
            ->where('payable_id', $agent->id);

            $agent_payouts = AgentPayout::select(DB::raw('id, "payout" as transaction_type, NULL as order_id, NULL as dependent_task_id, NULL as task_type_id, NULL as location_id, NULL as appointment_duration, NULL as task_status, NULL as allocation_type, amount, NULL as type, NULL as meta, NULL as dr, NULL as cr, created_at'))
            ->where('agent_id', $agent->id)->where('status', 1);
        
            if(!empty($request->from_date) && !empty($request->to_date)){
                $orders = Order::where('driver_id', $id)->whereBetween('order_time', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"])->pluck('id')->toArray();
            }else{
                $orders = Order::where('driver_id', $id)->pluck('id')->toArray();
            }
            if (isset($orders)) {
                $tasks = Task::whereIn('order_id', $orders)->whereIn('task_status', [4,5])
                ->with(['location','tasktype','order.customer'])
                ->select(DB::raw('id, "task" as transaction_type, order_id, dependent_task_id, task_type_id, location_id, appointment_duration, task_status, allocation_type, NULL as amount, NULL as type, NULL as meta, NULL as dr, NULL as cr, created_at'))
                ->union($payments)
                ->union($wallet_transactions)
                ->union($agent_payouts)
                ->orderBy('created_at', 'DESC')
                ->orderBy('order_id', 'DESC')
                ->paginate($limit, $page);
    
                $totalCashCollected = 0;
                foreach($tasks as $task){
                    $task->meta = json_decode($task->meta);
                    if($task->transaction_type == "wallet"){
                        $task->amount = sprintf("%.2f", $task->amount / 100);
                    }
                    if(!empty($task->order->cash_to_be_collected)){
                        $totalCashCollected += $task->order->cash_to_be_collected;
                    }
                }
            }

            // $payments = $tasks->merge($payments)->sortByDesc('created_at')->values()->all();
            // $payments = $this->paginate($payments, $limit, $page);
        }
        // $data['debit'] = $debit;
        // $data['credit'] = $credit;
        
        $data['order_cost'] = $order_cost ?? 0;
        $data['driver_cost'] = $driver_cost;
        $data['lifetime_earnings'] = $order_cost;
        $data['cash_to_be_collected'] = $cash;
        $data['wallet_balance'] = $final_balance;
        $data['payments'] = $tasks;
        $data['totalCashCollected'] = $totalCashCollected;

        return response()->json($data);
    }
}
