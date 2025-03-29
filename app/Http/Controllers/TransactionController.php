<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Deposit;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Actions\UpdatePocketTransaction;

class TransactionController extends Controller
{
    public function index(Request $request) {
        if(
            $request->has('order_id') &&
            $trx = Transaction::where('order_id', $request->order_id)->first()
        ){
            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $trx->order_id,
                    'status' => $trx->status
                ]
            ]);
        }

        $query = Transaction::query();

        if( $request->user()->role == User::ROLE_USER )
        {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $trx = Transaction::where('order_id', $request->order_id)->first();

        if(! $trx) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $trx->order_id,
                'status' => $trx->status
            ]
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdraw',
        ]);

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'order_id' => Str::lower( Str::ulid() ),
            'amount' => $request->amount,
            'type' => $request->type,
            'status' => Transaction::STATUS_PENDING,
        ]);

        Log::info('Transaction created {order_id}', [
            'order_id' => $transaction->order_id
        ]);
        /**
         * ----------------------------
         * Request to Third Party
         * ----------------------------
         */
        $callback = (new Deposit($transaction->order_id, $transaction->amount))->send();

        Log::info('callback'. json_encode($callback));
        /**
         * -----------------------------------------------------
         * in real project, usually we handle it via webhook
         * but for the sake of simplicity, we handle it here
         * -----------------------------------------------------
         */
        if($callback->success)
        {

            $updatedTransaction = Transaction::where('order_id', $callback->data->order_id)->first();

            if($updatedTransaction)
            {
                Log::info('Transaction update after {order_id}', [
                    'order_id' => $transaction->order_id
                ]);
                $updatedTransaction->status = Transaction::STATUS_SUCCESS;
                $updatedTransaction->trx_id = $callback->data->trxId;
                $updatedTransaction->save();

                UpdatePocketTransaction::handle($updatedTransaction);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ])
         // info for the next postman's request
        ->withCookie(cookie('latest_order_id', $transaction->order_id));
    }
}
