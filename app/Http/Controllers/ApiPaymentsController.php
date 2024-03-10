<?php

namespace App\Http\Controllers;

use App\Models\Cashout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Deposit;


class ApiPaymentsController extends Controller
{
    public  function cashoutFunds(Request $request)
    {
        //get user details
        $user = User::where('id',$request->input('id'))->first();
        Log::alert($user);
        if (!$user){
            return response()->json(['error','Contact Admin']);
        }
        //check amt
        if($user->balance < $request->input('amt')) {
            return response()->json(['error', 'you dont have enough balance!']);
        }
        //deduct user balance
        $newBalance = $user->balance - $request->input('amt');
        User::where('id', $request->input('id'))
            ->update(['balance' => $newBalance]);
        //create user
        $cashout = new Cashout([
            'user_id' => $request->input('id'),
            'amount' => $request->input('amt'),
            'trx_id' => Str::random(10),
            'phone' => $request->input('ph'),
        ]);

        $cashout->save();

        return response()->json(['success', 'Payments Processed!']);

    }

    public function getUserTransactions(Request $request)
    {
        $user = $request->user();

        try {
            // Fetch deposits and set transaction_type to 'deposit'
            $deposits = Deposit::where('user_id', $user->id)
                ->select('amount', 'status', 'phone', 'created_at as time')
                ->get()
                ->map(function ($deposit) {
                    $deposit['transaction_type'] = 'deposit';
                    return $deposit;
                });


            // Fetch cashouts and set transaction_type to 'cashout'
            $cashouts = Cashout::where('user_id', $user->id)
                ->select('amount', 'status', 'phone', 'created_at as time')
                ->get()
                ->map(function ($cashout) {
                    $cashout['transaction_type'] = 'cashout';
                    return $cashout;
                });
            Log::alert($cashouts);
            // Merge deposits and cashouts
            $transactions = $deposits->merge($cashouts);

            //Log::alert($transactions);

            // Sort transactions by time
            $transactions = $transactions->sortByDesc('id');


            return response()->json(['transactions' => $transactions], 200);
        } catch (\Exception $e) {
            Log::alert($e);
            return response()->json(['error' => 'Failed to fetch transactions.'], 500);
        }
    }



}
