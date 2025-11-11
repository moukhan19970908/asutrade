<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Фильтр по категории
        if ($request->has('category') && $request->get('category') != null) {
            $query->where('category_id', $request->category);
        }

        // Поиск по названию
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Фильтр по наличию
        if ($request->has('in_stock')) {
            $query->where('in_stock', $request->in_stock);
        }

        $products = $query->paginate(12);
        $categories = Category::all();


        return view('catalog.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Получаем остатки товара
        $stockInfo = $this->getProductStockInfo($product);

        // Получаем информацию о персональной скидке
        $discountInfo = $this->getPersonalDiscountInfo($product);

        $popularProducts = Product::where('in_stock', true)
            ->whereNotNull('image') // Только товары с изображениями
            ->with('category')
            ->orderBy('created_at', 'desc') // Сначала новые
            ->take(4)
            ->get();

        // Если товаров с изображениями меньше 8, добавляем остальные
        if ($popularProducts->count() < 4) {
            $additionalProducts = Product::where('in_stock', true)
                ->whereNull('image')
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->take(4 - $popularProducts->count())
                ->get();

            $popularProducts = $popularProducts->merge($additionalProducts);
        }

        return view('catalog.show', compact('product', 'popularProducts','relatedProducts', 'stockInfo', 'discountInfo'));
    }

    public function category(Category $category)
    {
        $products = $category->products()->paginate(12);
        $categories = Category::all();

        return view('catalog.index', compact('products', 'categories', 'category'));
    }

    private function getProductStockInfo(Product $product)
    {
        $user = Auth::user();

        if ($user && $user->warehouse) {
            // Авторизованный пользователь - показываем остаток только в его складе
            $stock = $product->warehouses()
                ->where('warehouse_id', $user->warehouse_id)
                ->first();

            return [
                'type' => 'user_warehouse',
                'warehouse_name' => $user->warehouse->name,
                'stock' => $stock ? $stock->pivot->stock : 0,
                'available' => $stock && $stock->pivot->stock > 0
            ];
        } else {
            // Неавторизованный пользователь - показываем остатки во всех складах
            $warehouses = Warehouse::all();
            $stockData = [];
            $totalStock = 0;

            foreach ($warehouses as $warehouse) {
                $stock = $product->warehouses()
                    ->where('warehouse_id', $warehouse->id)
                    ->first();

                $stockAmount = $stock ? $stock->pivot->stock : 0;
                $totalStock += $stockAmount;

                $stockData[] = [
                    'warehouse_name' => $warehouse->name,
                    'stock' => $stockAmount
                ];
            }

            return [
                'type' => 'all_warehouses',
                'warehouses' => $stockData,
                'total_stock' => $totalStock,
                'available' => $totalStock > 0
            ];
        }
    }

    private function getPersonalDiscountInfo(Product $product)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'has_discount' => false,
                'original_price' => $product->price,
                'discounted_price' => $product->price,
                'discount_percent' => 0
            ];
        }

        $discount = $product->getPersonalDiscountForUser($user->id);

        if ($discount) {
            $discountedPrice = $product->getDiscountedPrice($user->id);
            return [
                'has_discount' => true,
                'original_price' => $product->price,
                'discounted_price' => $discountedPrice,
                'discount_percent' => $discount->discount_percent,
                'description' => $discount->description
            ];
        }

        return [
            'has_discount' => false,
            'original_price' => $product->price,
            'discounted_price' => $product->price,
            'discount_percent' => 0
        ];
    }
}
