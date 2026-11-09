<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Страницы брендов.
 *
 * До этого бренды были в базе, но не имели ни одной ссылки на сайте:
 * логотипы на главной выводились без <a>, маршрута не существовало.
 * Для автозапчастей запрос «бренд + товар» — самый частый, поэтому
 * каждому бренду нужна своя посадочная страница.
 */
class BrandController extends Controller
{
    /** Товаров на странице бренда. */
    protected const PER_PAGE = 24;

    /**
     * GET /brands
     */
    public function index(): View
    {
        $brands = Brand::query()
            ->withCount('products')
            ->orderByDesc('is_popular')
            ->orderBy('name')
            ->get();

        return view('brands.index', compact('brands'));
    }

    /**
     * GET /brands/{brand}
     */
    public function show(Request $request, Brand $brand): View
    {
        $products = $brand->products()
            ->with('category')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderByDesc('in_stock')
            ->orderBy('name')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // Категории, в которых у бренда есть товары, — перелинковка
        // между двумя осями каталога: тип детали и производитель.
        $categories = Category::query()
            ->whereIn('id', $brand->products()->select('category_id'))
            ->withCount(['products' => fn ($query) => $query->where('brand_id', $brand->id)])
            ->orderBy('name')
            ->get();

        return view('brands.show', compact('brand', 'products', 'categories'));
    }
}
