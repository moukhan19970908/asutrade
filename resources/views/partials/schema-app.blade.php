{{--
    MobileApplication — карточка приложения для поиска.

    aggregateRating нет намеренно: отзывов у нас нет, а выдуманные
    оценки нарушают правила Google.
--}}
@php
    $appSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'MobileApplication',
        '@id' => route('mobile-app.index').'#app',
        'name' => config('mobile_app.name'),
        'url' => route('mobile-app.index'),
        'applicationCategory' => 'ShoppingApplication',
        'operatingSystem' => 'Android '.config('mobile_app.android_min').'+, iOS '.config('mobile_app.ios_min').'+',
        'softwareVersion' => $apk['version'] ?? null,
        'installUrl' => config('mobile_app.ios_url'),
        'downloadUrl' => route('mobile-app.download'),
        'publisher' => ['@id' => rtrim(url('/'), '/').'/#organization'],
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'KZT',
        ],
    ], static fn ($value) => $value !== null);
@endphp
<script type="application/ld+json">@json($appSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
