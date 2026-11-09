{{--
    BreadcrumbList из того же $trail, что и видимые крошки
    (partials.breadcrumbs), — разметка обязана повторять вёрстку.
--}}
@php
    $schemaTrail = array_merge(
        [['name' => 'Главная', 'url' => route('home')]],
        collect($trail)->map(fn ($crumb) => [
            'name' => $crumb['name'],
            'url' => $crumb['url'] ?? null,
        ])->all(),
    );

    $breadcrumbsSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_map(
            static fn (array $crumb, int $index) => array_filter([
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'] ?? null,
            ], static fn ($value) => $value !== null),
            $schemaTrail,
            array_keys($schemaTrail),
        ),
    ];
@endphp
<script type="application/ld+json">@json($breadcrumbsSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
