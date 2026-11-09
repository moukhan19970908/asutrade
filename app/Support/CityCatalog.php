<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Каталог в разрезе города.
 *
 * Городские страницы имеют право на существование только потому, что
 * остатки в 1С ведутся по складам: список товаров в Актау и в Актобе
 * действительно разный. Без этого три страницы с подставленным названием
 * города были бы дорвеями, за которые Google наказывает.
 */
class CityCatalog
{
    /**
     * Ниже этого числа позиций страница считается пустой:
     * уходит под noindex и не попадает в карту сайта.
     */
    public const MIN_PRODUCTS = 3;

    /** Кеш соответствия город → id складов, секунд. */
    protected const WAREHOUSE_CACHE_TTL = 3600;

    /**
     * id складов города. Пустой массив — соответствие не настроено
     * или склад с таким именем не заведён.
     *
     * @return array<int>
     */
    public static function warehouseIds(string $citySlug): array
    {
        return Cache::remember(
            'city-warehouses.'.$citySlug,
            self::WAREHOUSE_CACHE_TTL,
            static function () use ($citySlug) {
                $names = (array) config('stores.cities.'.$citySlug.'.warehouses', []);

                if ($names === []) {
                    return [];
                }

                return Warehouse::query()
                    ->whereIn(
                        \Illuminate\Support\Facades\DB::raw('LOWER(TRIM(name))'),
                        array_map(static fn ($name) => mb_strtolower(trim((string) $name)), $names),
                    )
                    ->pluck('id')
                    ->all();
            },
        );
    }

    /**
     * Товары категории (вместе с подкатегориями), лежащие на складах города.
     */
    public static function products(Category $category, string $citySlug): Builder
    {
        $warehouseIds = self::warehouseIds($citySlug);

        $categoryIds = collect([$category->id])
            ->merge($category->children()->pluck('id'))
            ->all();

        return Product::query()
            ->whereIn('category_id', $categoryIds)
            ->whereHas('warehouses', fn ($query) => $query
                ->whereIn('warehouses.id', $warehouseIds)
                ->where('warehouse_product.stock', '>', 0));
    }

    /**
     * Сколько позиций категории есть в городе.
     */
    public static function count(Category $category, string $citySlug): int
    {
        if (self::warehouseIds($citySlug) === []) {
            return 0;
        }

        return self::products($category, $citySlug)->count();
    }

    /**
     * Категории верхнего уровня, у которых в городе есть товары,
     * с количеством позиций. Используется и на странице города,
     * и при сборке карты сайта.
     *
     * @return Collection<int, array{category: Category, count: int}>
     */
    public static function categoriesWithStock(string $citySlug): Collection
    {
        if (self::warehouseIds($citySlug) === []) {
            return collect();
        }

        return Category::query()
            ->withoutGlobalScopes()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get()
            ->map(static fn (Category $category) => [
                'category' => $category,
                'count' => self::count($category, $citySlug),
            ])
            ->filter(static fn (array $row) => $row['count'] > 0)
            ->values();
    }

    /**
     * Город настроен и в нём вообще есть остатки.
     */
    public static function configured(string $citySlug): bool
    {
        return self::warehouseIds($citySlug) !== [];
    }
}
