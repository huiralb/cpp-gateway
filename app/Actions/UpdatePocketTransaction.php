<?php
namespace App\Actions;

use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class UpdatePocketTransaction
{
    public static function handle(Transaction $transaction)
    {
        $pocket = Pocket::where('user_id', $transaction->user_id)->first();

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
