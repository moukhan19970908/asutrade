{{--
    FAQPage из того же $faq, что и видимый блок.

    Важно про ожидания: расширенного вида в Google это не даст — FAQ rich
    results отключены 7 мая 2026. Разметка стоит ради другого потребителя:
    ChatGPT, Perplexity и Claude разбирают FAQPage и вытаскивают из неё
    готовые ответы. Размечаем только то, что видно на странице.
--}}
@if(!empty($faq))
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn (array $item) => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['a'],
                ],
            ], array_values($faq)),
        ];
    @endphp
    <script type="application/ld+json">@json($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
@endif
