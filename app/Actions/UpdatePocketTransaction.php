<?php
namespace App\Actions;

use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class UpdatePocketTransaction
{
    public static function handle(Transaction $transaction, $callback)
    {
        $pocket = Pocket::where('user_id', $transaction->user_id)->first();
        Log::info('Transaction update after {order_id}', [
            'order_id' => $transaction->order_id
        ]);
        $transaction->status = Transaction::STATUS_SUCCESS;
        $transaction->trx_id = $callback->data->trxId;
        $transaction->save();

        if($transaction->type == Transaction::TYPE_DEPOSIT)
        {
            $pocket->increment('amount', $transaction->amount);
        }

        if($transaction->type == Transaction::TYPE_WITHDRAW)
        {
            $pocket->decrement('amount', $transaction->amount);
        }

        Log::info("Pocket updated for transaction: {$transaction->id}");
    }
}
