<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Воздушный фильтр',
                'image' => null,
            ],
            [
                'name' => 'Масляный фильтр',
                'image' => null,
            ],
            [
                'name' => 'Салонный фильтр',
                'image' => null,
            ],
            [
                'name' => 'Топливный фильтр',
                'image' => null,
            ],
            [
                'name' => 'Моторные масла',
                'image' => null,
            ],
            [
                'name' => 'Смазка',
                'image' => null,
            ],
            [
                'name' => 'Тормозная жидкость',
                'image' => null,
            ],
            [
                'name' => 'Автохимия',
                'image' => null,
            ],
            [
                'name' => 'Охлаждающие жидкости',
                'image' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
