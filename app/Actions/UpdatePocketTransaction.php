<?php
namespace App\Actions;

use App\Models\Pocket;
use App\Models\Transaction;

class UpdatePocketTransaction
{
    public function __invoke(Transaction $transaction)
    {
        if($transaction->type == Transaction::TYPE_DEPOSIT)
        {
            $transaction->user->pocket()->increment('amount', $transaction->amount);
        }

        if($transaction->type == Transaction::TYPE_WITHDRAW)
        {
            $transaction->user->pocket()->decrement('amount', $transaction->amount);
        }
    }
}
