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
            ->from('categories as parent')
            ->leftJoin('categories as child', 'child.parent_id', '=', 'parent.id')
            ->leftJoin('products', 'products.category_id', '=', 'child.id')
            ->whereNull('parent.parent_id')
            ->selectRaw('parent.id, parent.name, COUNT(products.id) as products_count')
            ->groupBy('parent.id', 'parent.name')
            ->get();
      //  dd($categories);


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
