<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'customer_name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'residence' => $this->faker->city(),
            'nationality' => $this->faker->country(),
            'contact' => $this->faker->phoneNumber(),
            'payment_method' => 'card',
            'payment_provider' => 'stripe',
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
            'currency' => 'USD',
            'total_amount_usd' => $this->faker->randomFloat(2, 5, 50),
            'exchange_rate' => $this->faker->randomFloat(4, 8, 15),
            'status' => 'pending',
            'order_number' => 'ORD-'.$this->faker->unique()->randomNumber(8),
            'payment_status' => 'pending',
            'paid_at' => null,
            'pdf_sent' => false,
            'pdf_sent_at' => null,
            'book_offered' => false,
            'book_offered_at' => null,
            'order_items' => [],
            'transaction_reference' => null,
        ];
    }

    public function paid(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }
}
