<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Support\CityCatalog;
use App\Support\Plural;
use Illuminate\Http\Response;

/**
 * /llms.txt — краткая карта сайта для языковых моделей (llmstxt.org).
 *
 * Google для своих AI-ответов такой файл не требует и прямо об этом пишет.
 * Файл нужен другому потребителю: ChatGPT, Perplexity и Claude разбирают
 * его, чтобы быстро понять, что за сайт, и не угадывать по вёрстке.
 *
 * Собирается из базы, а не лежит статикой: каталог приезжает из 1С,
 * написанный руками файл устарел бы после первой же выгрузки.
 */
class LlmsTxtController extends Controller
{
    protected const CACHE_TTL = 3600;

    /** Сколько брендов перечислять: файл должен оставаться обзорным. */
    protected const MAX_BRANDS = 30;

    public function index(): Response
    {
        $body = cache()->remember('llms.txt', self::CACHE_TTL, fn () => $this->build());

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    protected function build(): string
    {
        $lines = [];

        $lines[] = '# Asu Trade';
        $lines[] = '';
        $lines[] = '> Магазин автозапчастей в Казахстане: моторные и трансмиссионные масла, '
            .'воздушные, масляные, салонные и топливные фильтры, антифризы, смазки и автохимия. '
            .'Пять магазинов в Актау, Жанаозене и Актобе, доставка по всей стране.';
        $lines[] = '';
        $lines[] = 'Телефон: '.config('stores.phone_display').'. WhatsApp: '.config('stores.whatsapp').'.';
        $lines[] = 'Доставка бесплатна при заказе от '
            .number_format((int) config('stores.free_delivery_from'), 0, '.', ' ').' ₸, самовывоз из магазинов бесплатный.';
        $lines[] = 'Цены в тенге (KZT). Данные актуальны на '.now()->format('d.m.Y').'.';
        $lines[] = '';

        $this->section($lines, 'Основные страницы', [
            '- ['.'Каталог]('.route('catalog.index').'): все категории товаров',
            '- [Бренды]('.route('brands.index').'): производители масел, фильтров и автохимии',
            '- [Магазины]('.route('stores.index').'): адреса и карты проезда',
            '- [Доставка]('.route('delivery').'): сроки, стоимость, самовывоз',
            '- [О компании]('.route('about').'): чем занимается Asu Trade',
            '- [Приложение]('.route('mobile-app.index').'): мобильное приложение ASU Auto для Android и iOS',
            '- [Техподдержка]('.route('support').'): связь с менеджером',
        ]);

        $this->section($lines, 'Категории каталога', $this->categoryLines());

        $brands = $this->brandLines();

        if ($brands !== []) {
            $this->section($lines, 'Бренды', $brands);
        }

        $cities = $this->cityLines();

        if ($cities !== []) {
            $this->section($lines, 'Наличие по городам', $cities);
        }

        $this->section($lines, 'Правовые документы', [
            '- [Пользовательское соглашение]('.route('user-agreement').')',
            '- [Политика конфиденциальности]('.route('privacy-policy').')',
        ]);

        return implode("\n", $lines)."\n";
    }

    /**
     * @param  array<string>  $lines
     * @param  array<string>  $items
     */
    protected function section(array &$lines, string $title, array $items): void
    {
        if ($items === []) {
            return;
        }

        $lines[] = '## '.$title;
        $lines[] = '';

        foreach ($items as $item) {
            $lines[] = $item;
        }

        $lines[] = '';
    }

    /**
     * @return array<string>
     */
    protected function categoryLines(): array
    {
        return Category::query()
            ->withoutGlobalScopes()
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('name')
            ->get()
            ->map(function (Category $category) {
                $count = (int) $category->products_count;

                return '- ['.$category->name.']('.route('catalog.category', $category).')'
                    .($count > 0 ? ': '.Plural::with($count, 'товар', 'товара', 'товаров') : '');
            })
            ->all();
    }

    /**
     * @return array<string>
     */
    protected function brandLines(): array
    {
        return Brand::query()
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->limit(self::MAX_BRANDS)
            ->get()
            ->sortBy('name')
            ->map(fn (Brand $brand) => '- ['.$brand->name.']('.route('brands.show', $brand).')'
                .': '.Plural::with((int) $brand->products_count, 'товар', 'товара', 'товаров'))
            ->values()
            ->all();
    }

    /**
     * Города с собственным складом — только для них есть страницы наличия.
     *
     * @return array<string>
     */
    protected function cityLines(): array
    {
        $lines = [];

        foreach (config('stores.cities') as $slug => $city) {
            $addresses = collect($city['points'])->pluck('street')->implode('; ');

            $line = '- ['.$city['name'].']('.route('stores.show', $slug).'): '.$addresses;

            if (CityCatalog::configured($slug)) {
                $categories = CityCatalog::categoriesWithStock($slug);

                if ($categories->isNotEmpty()) {
                    $line .= '. В наличии: '.$categories
                        ->map(fn (array $row) => $row['category']->name.' ('.$row['count'].')')
                        ->implode(', ');
                }
            }

            $lines[] = $line;
        }

        return $lines;
    }
}
