<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->select('categories.*')
            ->selectRaw('
        COUNT(DISTINCT p1.id) +
        COUNT(DISTINCT p2.id) as total_products_count
    ')
            ->leftJoin('products as p1', 'p1.category_id', '=', 'categories.id')
            ->leftJoin('categories as c2', 'c2.parent_id', '=', 'categories.id')
            ->leftJoin('products as p2', 'p2.category_id', '=', 'c2.id')
            ->whereNull('categories.parent_id')
            ->groupBy('categories.id')
            ->get();


        // Получаем популярные товары (с изображениями и в наличии)
        $popularProducts = Product::where('in_stock', true)
            ->whereNotNull('image') // Только товары с изображениями
            ->with('category')
            ->orderBy('created_at', 'desc') // Сначала новые
            ->take(8)
            ->get();

        // Если товаров с изображениями меньше 8, добавляем остальные
        if ($popularProducts->count() < 8) {
            $additionalProducts = Product::where('in_stock', true)
                ->whereNull('image')
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->take(8 - $popularProducts->count())
                ->get();

            $popularProducts = $popularProducts->merge($additionalProducts);
        }

        $popularBrands = \App\Models\Brand::where('is_popular', true)->get();

        // Новые поступления (последние добавленные товары)
        $newProducts = Product::where('in_stock', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'popularProducts', 'popularBrands', 'newProducts'));
    }

    public function delivery(){
        return view('delivery');
    }

    public function about(){
        return view('about');
    }
}
