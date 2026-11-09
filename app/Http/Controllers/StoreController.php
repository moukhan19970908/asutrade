<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Магазины сети.
 *
 * Города вынесены в отдельные страницы ради локального поиска:
 * запрос «автозапчасти Актау» раньше вести было некуда — адреса
 * существовали только строкой в футере.
 */
class StoreController extends Controller
{
    /**
     * GET /magaziny
     */
    public function index(): View
    {
        return view('stores.index', [
            'cities' => config('stores.cities'),
        ]);
    }

    /**
     * GET /magaziny/{city}
     */
    public function show(string $city): View
    {
        $data = config('stores.cities.'.$city);

        if ($data === null) {
            throw new NotFoundHttpException();
        }

        return view('stores.show', [
            'citySlug' => $city,
            'city' => $data,
        ]);
    }
}
