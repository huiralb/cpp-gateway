<?php

namespace App\Http\Controllers;

use App\Services\Deposit;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\UpdatePocketTransaction;
use Illuminate\Support\Facades\Log;

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

        return response()->noContent();
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

        /**
         * -----------------------------------------------------
         * in real project, usually we handle it via webhook
         * but for the sake of simplicity, we handle it here
         * -----------------------------------------------------
         */
        if($callback->success)
        {
            Log::info('Transaction updated {order_id}', [
                'order_id' => $transaction->order_id
            ]);

            $updatedTransaction = Transaction::where('order_id', $callback->data->order_id)->first();

            if($updatedTransaction)
            {
                $updatedTransaction->status = Transaction::STATUS_SUCCESS;
                $updatedTransaction->trx_id = $callback->data->trxId;
                $updatedTransaction->save();

                (new UpdatePocketTransaction($updatedTransaction));
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
