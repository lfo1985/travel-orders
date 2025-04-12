<?php

namespace Database\Factories;

use App\Enums\StatusOrderEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'costumer_name' => $this->faker->name(),
            'destination_name' => $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'return_date' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'status' => $this->faker->randomElement([
                StatusOrderEnum::REQUESTED->value,
                StatusOrderEnum::APPROVED->value,
                StatusOrderEnum::CANCELED->value,
            ]),
        ];
    }
}
