<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Support\CityCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Каталог категории в конкретном городе: /catalog/{category}/{city}
 *
 * Страница существует ради локального спроса («масляные фильтры Актау»)
 * и опирается на остатки по складу города — собственные данные, а не
 * подставленное в шаблон название города.
 */
class CityCatalogController extends Controller
{
    protected const PER_PAGE = 24;

    public function show(Request $request, Category $category, string $city): View
    {
        $cityData = config('stores.cities.'.$city);

        if ($cityData === null) {
            throw new NotFoundHttpException();
        }

        // Склад города не настроен — отдавать страницу нельзя: она была бы
        // копией соседнего города, то есть ровно тем дорвеем, за который
        // накладывают санкции. Проверить привязку: php artisan stores:warehouses
        if (! CityCatalog::configured($city)) {
            throw new NotFoundHttpException();
        }

        $query = CityCatalog::products($category, $city)->with(['category', 'brand']);

        $brands = Brand::query()
            ->whereIn('id', (clone $query)->select('brand_id')->whereNotNull('brand_id'))
            ->orderBy('name')
            ->get();

        $activeBrand = null;

        if ($request->filled('brand')) {
            $activeBrand = Brand::where('slug', $request->string('brand'))->first();

            if ($activeBrand) {
                $query->where('brand_id', $activeBrand->id);
            }
        }

        $total = CityCatalog::count($category, $city);

        $products = $query->orderBy('name')->paginate(self::PER_PAGE)->withQueryString();

        // Та же категория в других городах — перелинковка между спицами.
        $otherCities = collect(config('stores.cities'))
            ->except($city)
            ->filter(fn ($data, $slug) => CityCatalog::count($category, $slug) > 0)
            ->map(fn ($data, $slug) => [
                'slug' => $slug,
                'name' => $data['name'],
                'prepositional' => $data['name_prepositional'],
                'count' => CityCatalog::count($category, $slug),
            ]);

        // Другие категории в этом же городе.
        $otherCategories = CityCatalog::categoriesWithStock($city)
            ->reject(fn (array $row) => $row['category']->id === $category->id)
            ->take(12);

        return view('catalog.city', [
            'category' => $category,
            'citySlug' => $city,
            'city' => $cityData,
            'products' => $products,
            'total' => $total,
            'brands' => $brands,
            'activeBrand' => $activeBrand,
            'otherCities' => $otherCities,
            'otherCategories' => $otherCategories,

            // Тонкие варианты из индекса убираем — требование к страницам,
            // создаваемым по шаблону.
            'metaRobots' => $total < CityCatalog::MIN_PRODUCTS ? 'noindex, follow' : null,
        ]);
    }
}
