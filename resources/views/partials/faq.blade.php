{{--
    Видимый блок «Вопрос — ответ».

    $faq — массив ['q' => вопрос, 'a' => ответ]. Тот же массив уходит
    в partials.schema-faq, чтобы разметка не разошлась с текстом.

    Ответ пишется самодостаточным: он должен читаться в отрыве от
    остальной страницы — так его забирают и поисковики, и AI-ассистенты.
--}}
@if(!empty($faq))
    <section class="mt-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ $faqTitle ?? 'Частые вопросы' }}</h2>

        <div class="divide-y divide-gray-200 border-t border-b border-gray-200">
            @foreach($faq as $item)
                <details class="group py-4">
                    <summary class="flex cursor-pointer items-center justify-between font-medium text-gray-900 list-none">
                        <span>{{ $item['q'] }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform group-open:rotate-180"></i>
                    </summary>
                    <p class="mt-3 text-gray-600 text-sm leading-relaxed">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
@endif
