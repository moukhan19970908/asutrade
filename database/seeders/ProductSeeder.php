<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Воздушные фильтры
            [
                'category_id' => 1,
                'brand_id' => 1,
                'name' => 'Возд. фильтр AF-983 NF C3725 17801-28010',
                'description' => 'Воздушный фильтр для двигателя',
                'price' => 650.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 2,
                'name' => 'Воздушный фильтр AF-977',
                'description' => 'Высококачественный воздушный фильтр',
                'price' => 620.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 3,
                'name' => 'Возд. фильтр AF-953 NF C2119',
                'description' => 'Воздушный фильтр для легковых автомобилей',
                'price' => 650.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 4,
                'name' => 'Возд. фильтр AF-926 NF',
                'description' => 'Универсальный воздушный фильтр',
                'price' => 1050.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 5,
                'name' => 'Возд. фильтр AF-678 NF',
                'description' => 'Воздушный фильтр для грузовых автомобилей',
                'price' => 725.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 1,
                'name' => 'Возд. фильтр AF-632 NF DA3664 835622',
                'description' => 'Воздушный фильтр с улучшенной фильтрацией',
                'price' => 870.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 2,
                'name' => 'Возд. фильтр AF-537 NF 0058 C3689 A1120940604',
                'description' => 'Премиум воздушный фильтр',
                'price' => 890.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 1,
                'brand_id' => 3,
                'name' => 'Воздушный фильтр AF-528 NF',
                'description' => 'Спортивный воздушный фильтр',
                'price' => 1050.00,
                'in_stock' => true,
            ],
            // Масляные фильтры
            [
                'category_id' => 2,
                'brand_id' => 1,
                'name' => 'Масляный фильтр OF-123',
                'description' => 'Высококачественный масляный фильтр',
                'price' => 450.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 2,
                'brand_id' => 4,
                'name' => 'Масляный фильтр OF-456',
                'description' => 'Масляный фильтр для дизельных двигателей',
                'price' => 580.00,
                'in_stock' => true,
            ],
            // Моторные масла
            [
                'category_id' => 5,
                'brand_id' => 1,
                'name' => 'Моторное масло Shell 5W-30 4л',
                'description' => 'Синтетическое моторное масло Shell',
                'price' => 8500.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 5,
                'brand_id' => 3,
                'name' => 'Моторное масло Total 10W-40 4л',
                'description' => 'Полусинтетическое моторное масло Total',
                'price' => 7200.00,
                'in_stock' => true,
            ],
            [
                'category_id' => 5,
                'brand_id' => 2,
                'name' => 'Моторное масло Liqui Moly 5W-30 4л',
                'description' => 'Премиум моторное масло Liqui Moly',
                'price' => 12000.00,
                'in_stock' => true,
            ],
            // Тормозная жидкость
            [
                'category_id' => 7,
                'brand_id' => 2,
                'name' => 'Тормозная жидкость DOT-4 1л',
                'description' => 'Высококачественная тормозная жидкость',
                'price' => 1200.00,
                'in_stock' => true,
            ],
            // Охлаждающие жидкости
            [
                'category_id' => 9,
                'brand_id' => 4,
                'name' => 'Антифриз Sintec -40°C 5л',
                'description' => 'Концентрированный антифриз',
                'price' => 3500.00,
                'in_stock' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
