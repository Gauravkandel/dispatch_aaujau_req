<?php

namespace App\Http\Controllers\Api;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Model\{Agent, Transaction, PaymentOption};
use App\Http\Controllers\Controller;

class WalletController extends Controller{
    use ApiResponser;

    # get my wallet details 
    public function getFindMyWalletDetails(Request $request){
    	$user = Auth::user();
        $user = Agent::find($user->id);
        $paginate = $request->has('limit') ? $request->limit : 12;
        $transactions = Transaction::where('payable_id', $user->id)->orderBy('id', 'desc')->paginate($paginate);
        foreach($transactions as $trans){
            $trans->meta = json_decode($trans->meta);
            $trans->amount = sprintf("%.2f", $trans->amount / 100);
        }
        $data = ['wallet_amount' => $user->balanceFloat, 'transactions' => $transactions];
        return $this->success($data, '', 200);
    }


    # credit wallet set 
    public function creditAgentWallet(Request $request)
    {   
        if($request->has('auth_token')){
            $user = Agent::where('access_token', $request->auth_token)->first();
        }
        else{
            $user = Auth::user();
        }
       
        if($user){
            $credit_amount = $request->amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $payment_option = '';
                if($request->has('payment_option_id') && ($request->payment_option_id > 0) ){
                    $payment_option = PaymentOption::where('id', $request->payment_option_id)->value('title');
                }
                
                $wallet->depositFloat($credit_amount, [
                    'type' => 'wallet',
                    'transaction_type' => 'wallet_topup',
                    'transaction_id' => $request->transaction_id,
                    'payment_option' => $payment_option,
                    'description' => 'Wallet has been <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>'
                ]);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                return $this->success($response, $message, 201);
            }
            else{
                return $this->error('Amount is not sufficient', 402);
            }
        }
        else{
            return $this->error('Invalid User', 402);
        }
    }
}
