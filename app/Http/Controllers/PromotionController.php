<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Акции.
 *
 * Публичных акций в проекте нет — скидки хранятся в personal_discounts
 * и привязаны к пользователю. Поэтому страница показывает скидки
 * текущего клиента, а гостю объясняет, где их увидеть.
 *
 * Раньше пункт «Акции» в шапке вёл на href="#".
 */
class PromotionController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $products = collect();

        if ($user) {
            $products = Product::query()
                ->with(['category', 'brand'])
                ->whereHas('personalDiscounts', fn ($query) => $query
                    ->where('user_id', $user->id)
                    ->where('is_active', true))
                ->with(['personalDiscounts' => fn ($query) => $query
                    ->where('user_id', $user->id)
                    ->where('is_active', true)])
                ->orderBy('name')
                ->get();
        }

        return view('promotions.index', compact('products'));
    }
}
