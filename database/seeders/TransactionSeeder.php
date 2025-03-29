<?php

namespace Database\Seeders;

use App\Models\Pocket;
use App\Models\Transaction;
use COM;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    protected $userId = 1;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Transaction::truncate();
        Pocket::truncate();

        Transaction::factory(5)->create([
            'user_id' => $this->userId,
        ]);
        
        Transaction::factory(5)->create([
            'user_id' => $this->userId,
            'type' => Transaction::TYPE_DEPOSIT,
            'status' => Transaction::STATUS_SUCCESS
        ]);

        Pocket::create([
            'user_id' => $this->userId,
            'name' => 'Main Pocket',
            'amount' => $this->getAmount(),
            'description' => 'Main pocket for user',
        ]);
    }

    protected function getAmount() {
        $deposit = Transaction::where([
            'user_id' => $this->userId,
            'type' => Transaction::TYPE_DEPOSIT,
            'status' => Transaction::STATUS_SUCCESS
        ])->sum('amount');

        $withdraw = Transaction::where([
            'user_id' => $this->userId,
            'type' => Transaction::TYPE_WITHDRAW,
            'status' => Transaction::STATUS_SUCCESS
        ])->sum('amount');

        return $deposit - $withdraw;
    }
}
