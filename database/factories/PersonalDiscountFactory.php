<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalDiscount>
 */
class PersonalDiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'discount_percent' => $this->faker->randomFloat(2, 5, 50),
            'is_active' => $this->faker->boolean(80), // 80% вероятность что скидка активна
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
