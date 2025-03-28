<?php
namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class Deposit {
    public function __construct(
        protected string $orderId,
        protected float $amount
    ){}

    public function send()
    {
        $timestamp = now()->timestamp;

        Http::withHeader([
            'Authorization' => "Bearer ". $this->getToken()
        ])->post('https://yourdomain.com/deposit', [
            'order_id' => $this->orderId,
            'amount' => $this->amount,
            'timestamp' => $timestamp
        ]);

        return (object)[
            'success' => true,
            'data' => (object)[
                'order_id' => $this->orderId,
                'amount' => $this->amount,
                'timestamp' => $timestamp,
                'trxId' => str()->uuid()
            ]
        ];
    }

    private function getToken()
    {
        return base64_encode("irnovi");
    }
}
