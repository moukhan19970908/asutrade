<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Support\CityCatalog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * XML-карта сайта для поисковых систем.
 *
 * Отдаётся на лету и кешируется: каталог приезжает из 1С, файл на диске
 * устаревал бы между выгрузками.
 */
class SitemapController extends Controller
{
    /** Предел одного файла карты по спецификации sitemaps.org. */
    protected const MAX_URLS = 45000;

    /** На сколько кешировать готовый XML, секунд. */
    protected const CACHE_TTL = 3600;

    public function index(): Response
    {
        $xml = cache()->remember('sitemap.xml', self::CACHE_TTL, fn () => $this->build());

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    protected function build(): string
    {
        $urls = [];

        $urls[] = $this->url(route('home'), '1.0', 'daily');
        $urls[] = $this->url(route('catalog.index'), '0.9', 'daily');

        $urls[] = $this->url(route('brands.index'), '0.8', 'weekly');
        $urls[] = $this->url(route('stores.index'), '0.7', 'monthly');
        $urls[] = $this->url(route('promotions.index'), '0.4', 'weekly');
        $urls[] = $this->url(route('mobile-app.index'), '0.6', 'monthly');

        foreach (array_keys(config('stores.cities')) as $city) {
            $urls[] = $this->url(route('stores.show', $city), '0.7', 'monthly');
        }

        foreach (['about', 'delivery', 'support', 'user-agreement', 'privacy-policy'] as $page) {
            $urls[] = $this->url(route($page), '0.3', 'monthly');
        }

        Brand::query()
            ->orderBy('id')
            ->chunk(500, function ($brands) use (&$urls) {
                foreach ($brands as $brand) {
                    $urls[] = $this->url(route('brands.show', $brand), '0.7', 'weekly');
                }
            });

        // Глобальные скоупы скрывают часть каталога от отдельных пользователей —
        // для робота карта должна быть полной.
        Category::query()
            ->withoutGlobalScopes()
            ->orderBy('id')
            ->chunk(500, function ($categories) use (&$urls) {
                foreach ($categories as $category) {
                    $urls[] = $this->url(
                        route('catalog.category', $category),
                        '0.8',
                        'weekly',
                        $category->updated_at?->toAtomString(),
                    );
                }
            });

        // Городские страницы каталога: только те, где товаров достаточно.
        // Тонкие варианты в карту не кладём — они и так под noindex.
        foreach (array_keys(config('stores.cities')) as $citySlug) {
            foreach (CityCatalog::categoriesWithStock($citySlug) as $row) {
                if ($row['count'] < CityCatalog::MIN_PRODUCTS) {
                    continue;
                }

                $urls[] = $this->url(
                    route('catalog.city', ['category' => $row['category'], 'city' => $citySlug]),
                    '0.8',
                    'daily',
                );
            }
        }

        Product::query()
            ->withoutGlobalScopes()
            ->orderBy('id')
            ->chunk(500, function ($products) use (&$urls) {
                foreach ($products as $product) {
                    $urls[] = $this->url(
                        route('catalog.show', $product),
                        '0.7',
                        'weekly',
                        $product->updated_at?->toAtomString(),
                    );
                }
            });

        if (count($urls) > self::MAX_URLS) {
            Log::warning('Sitemap: превышен лимит URL, карту пора разбивать на части', [
                'count' => count($urls),
                'limit' => self::MAX_URLS,
            ]);

            $urls = array_slice($urls, 0, self::MAX_URLS);
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .implode('', $urls)
            .'</urlset>'."\n";
    }

    protected function url(string $loc, string $priority, string $changefreq, ?string $lastmod = null): string
    {
        return '  <url>'."\n"
            .'    <loc>'.htmlspecialchars($loc, ENT_XML1).'</loc>'."\n"
            .($lastmod ? '    <lastmod>'.$lastmod.'</lastmod>'."\n" : '')
            .'    <changefreq>'.$changefreq.'</changefreq>'."\n"
            .'    <priority>'.$priority.'</priority>'."\n"
            .'  </url>'."\n";
    }
}
