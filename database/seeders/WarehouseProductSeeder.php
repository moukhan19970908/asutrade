<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Product;

class WarehouseProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $products = Product::all();

        foreach ($products as $product) {
            foreach ($warehouses as $warehouse) {
                $stock = $warehouse->name === 'Актау' ? 10 : 5;
                $product->warehouses()->attach($warehouse->id, ['stock' => $stock]);
            }
        }
    }
}
