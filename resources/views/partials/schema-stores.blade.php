{{--
    Магазины сети. Адреса и телефон берутся из config/stores.php —
    того же источника, что футер и страницы городов.

    $onlyCity (необязательно) — слаг города: на странице города размечаем
    только его точки, иначе разметка разойдётся с содержимым страницы.

    Часы работы намеренно не указаны — на сайте их нет, а выдумывать
    данные в разметке нельзя.
--}}
@php
    $site = rtrim(url('/'), '/');
    $cities = config('stores.cities');

    if (! empty($onlyCity)) {
        $cities = array_intersect_key($cities, [$onlyCity => true]);
    }

    $storeNodes = [];

    foreach ($cities as $citySlug => $city) {
        foreach ($city['points'] as $point) {
            $storeNodes[] = [
                '@type' => 'AsuAuto',
                '@id' => $site.'/#store-'.$citySlug.'-'.$point['slug'],
                'name' => 'Asu Trade',
                'url' => route('stores.show', $citySlug),
                'image' => asset('img/logo.png'),
                'telephone' => config('stores.phone'),
                'parentOrganization' => ['@id' => $site.'/#organization'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $point['street'],
                    'addressLocality' => $city['name'],
                    'addressCountry' => 'KZ',
                ],
                'hasMap' => $point['map'],
            ];
        }
    }

    $storesSchema = [
        '@context' => 'https://schema.org',
        '@graph' => $storeNodes,
    ];
@endphp
<script type="application/ld+json">@json($storesSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
