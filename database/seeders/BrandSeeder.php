<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Shell',
                'logo' => null,
                'description' => 'Мировой производитель масел и смазок.',
                'is_popular' => true,
            ],
            [
                'name' => 'Liqui Moly',
                'logo' => null,
                'description' => 'Немецкие масла и автохимия.',
                'is_popular' => true,
            ],
            [
                'name' => 'Total',
                'logo' => null,
                'description' => 'Французские моторные масла.',
                'is_popular' => true,
            ],
            [
                'name' => 'Gazpromneft',
                'logo' => null,
                'description' => 'Российские масла и смазки.',
                'is_popular' => true,
            ],
            [
                'name' => 'Elf',
                'logo' => null,
                'description' => 'Французские масла для авто.',
                'is_popular' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
