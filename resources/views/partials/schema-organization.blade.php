{{--
    Organization + WebSite — общая разметка сайта, выводится на всех страницах.
    Данные берутся из футера layouts/app.blade.php: разметка обязана совпадать
    с тем, что видит посетитель, иначе Google считает её спамом.
--}}
@php
    $site = rtrim(url('/'), '/');

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => $site.'/#organization',
                'name' => 'Asu Trade',
                'url' => $site,
                'description' => 'Качественные автозапчасти для вашего автомобиля',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('img/logo.png'),
                ],
                'image' => asset('img/logo.png'),
                'telephone' => '+77788084030',
                'areaServed' => [
                    '@type' => 'Country',
                    'name' => 'Казахстан',
                ],
                'contactPoint' => [
                    [
                        '@type' => 'ContactPoint',
                        'telephone' => '+77788084030',
                        'contactType' => 'customer service',
                        'availableLanguage' => ['ru', 'kk'],
                    ],
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $site.'/#website',
                'url' => $site,
                'name' => 'Asu Trade',
                'inLanguage' => 'ru',
                'publisher' => ['@id' => $site.'/#organization'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">@json($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
