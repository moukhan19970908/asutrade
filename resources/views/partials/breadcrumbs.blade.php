{{--
    Видимые хлебные крошки.

    $trail — массив ['name' => ..., 'url' => ...]; «Главная» добавляется сама,
    у последнего элемента ссылку не передаём. Тот же $trail уходит
    в partials.schema-breadcrumbs, чтобы разметка не разошлась с вёрсткой.
--}}
<nav class="mb-6" aria-label="Хлебные крошки">
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-gray-500">
        <li><a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a></li>

        @foreach($trail as $crumb)
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li>
                @if(! empty($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="hover:text-blue-600">{{ $crumb['name'] }}</a>
                @else
                    <span class="text-gray-900">{{ $crumb['name'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
