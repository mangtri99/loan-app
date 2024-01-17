<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(1000, 10000);
        // get random user id from users table
        $userId = User::inRandomOrder()->first()->id;
        return [
            'amount' => $amount,
            'term' => fake()->numberBetween(1, 12),
            'balance' => 0,
            'type_id' => Type::WEEKLY,
            'status_id' => Status::PENDING,
            'user_id' => $userId,
        ];
    }
}
