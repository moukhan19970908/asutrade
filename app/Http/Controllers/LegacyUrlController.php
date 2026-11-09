<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

/**
 * Постоянные редиректы со старых адресов на слаги.
 *
 * Старые URL были проиндексированы, поэтому отвечаем 301, а не 302:
 * иначе накопленный вес ссылок не перейдёт на новый адрес.
 */
class LegacyUrlController extends Controller
{
    /**
     * /catalog/{id} → /product/{slug}
     */
    public function product(int $product): RedirectResponse
    {
        $model = Product::query()->withoutGlobalScopes()->findOrFail($product);

        return redirect()->route('catalog.show', $model, 301);
    }

    /**
     * /category/{id} → /catalog/{slug}
     */
    public function category(int $category): RedirectResponse
    {
        $model = Category::query()->withoutGlobalScopes()->findOrFail($category);

        return redirect()->route('catalog.category', $model, 301);
    }
}
