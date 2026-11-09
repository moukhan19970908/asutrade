{{--
    Product для страницы товара. Крошки — в partials.schema-breadcrumbs,
    общем для всех разделов.
    Ожидает: $product, $stockInfo, $discountInfo (их отдаёт CatalogController::show).
--}}
@php
    $site = rtrim(url('/'), '/');
    $productUrl = route('catalog.show', $product);

    // Цена уходит в разметку только тогда, когда она реально отрисована на странице.
    // Блок с ценой в catalog/show.blade.php показывается лишь авторизованному
    // пользователю со складом, поэтому гостю — и роботу Google — цены не видно.
    // Размечать то, чего на странице нет, Google прямо запрещает, а заодно это
    // не даёт персональной скидке клиента утечь в публичную разметку.
    $visiblePrice = null;

    if (($stockInfo['type'] ?? null) === 'user_warehouse') {
        $visiblePrice = ($discountInfo['has_discount'] ?? false)
            ? $discountInfo['discounted_price']
            : $product->price;
    }

    $offer = [
        '@type' => 'Offer',
        'url' => $productUrl,
        'priceCurrency' => 'KZT',
        'availability' => ($stockInfo['available'] ?? false)
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock',
        'seller' => ['@id' => $site.'/#organization'],
    ];

    if ($visiblePrice !== null) {
        $offer['price'] = number_format((float) $visiblePrice, 2, '.', '');
    }

    // aggregateRating / review намеренно отсутствуют: отзывов в проекте нет,
    // а выдуманные звёзды — нарушение правил Google и повод для санкций.
    $productSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        '@id' => $productUrl.'#product',
        'name' => $product->name,
        'url' => $productUrl,
        'description' => $product->description ?: null,
        'image' => $product->image ? asset('storage/'.$product->image) : null,
        'sku' => $product->code ?: null,
        'brand' => $product->brand ? ['@type' => 'Brand', 'name' => $product->brand->name] : null,
        'category' => $product->category->name ?? null,
        'offers' => $offer,
    ], static fn ($value) => $value !== null);

@endphp
<script type="application/ld+json">@json($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
