<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'trx_id' => fake()->uuid(),
            'amount' => fake()->randomFloat(2, 1, 100),
            'type' => fake()->randomElement(['deposit', 'withdraw']),
            'status' => fake()->randomElement(['pending', 'success', 'failed']),
        ];
    }
}
