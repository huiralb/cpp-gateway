<?php

namespace Database\Seeders;

use App\Models\Pocket;
use App\Models\Transaction;
use COM;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1;

        Transaction::truncate();
        Pocket::truncate();

        Transaction::factory(2)->create([
            'user_id' => $userId,
            'type' => Transaction::TYPE_DEPOSIT,
            'status' => Transaction::STATUS_SUCCESS,
        ]);
        Transaction::factory(2)->create([
            'user_id' => $userId,
            'type' => Transaction::TYPE_DEPOSIT,
            'status' => Transaction::STATUS_PENDING,
        ]);

        Pocket::create([
            'user_id' => $userId,
            'name' => 'Main Pocket',
            'amount' => Transaction::where([
                'user_id' => $userId,
                'type' => Transaction::TYPE_DEPOSIT,
                'status' => Transaction::STATUS_SUCCESS
            ])->sum('amount'),
            'description' => 'Main pocket for user',
        ]);
    }
}
