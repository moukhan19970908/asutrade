<?php

namespace Database\Seeders;

use App\Models\PersonalDiscount;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonalDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем несколько персональных скидок для тестирования
        $users = User::all();
        $products = Product::all();

        if ($users->count() > 0 && $products->count() > 0) {
            // Создаем персональные скидки для первых пользователей на первые товары
            $discounts = [
                [
                    'user_id' => $users->first()->id,
                    'product_id' => $products->first()->id,
                    'discount_percent' => 15.00,
                    'is_active' => true,
                    'description' => 'Персональная скидка для VIP клиента'
                ],
                [
                    'user_id' => $users->first()->id,
                    'product_id' => $products->skip(1)->first()->id,
                    'discount_percent' => 10.00,
                    'is_active' => true,
                    'description' => 'Скидка за постоянные покупки'
                ],
                [
                    'user_id' => $users->skip(1)->first()->id,
                    'product_id' => $products->first()->id,
                    'discount_percent' => 20.00,
                    'is_active' => true,
                    'description' => 'Специальное предложение'
                ]
            ];

            foreach ($discounts as $discount) {
                PersonalDiscount::updateOrCreate(
                    [
                        'user_id' => $discount['user_id'],
                        'product_id' => $discount['product_id']
                    ],
                    $discount
                );
            }

            // Создаем еще несколько случайных скидок
            PersonalDiscount::factory(5)->create();
        }
    }
}
