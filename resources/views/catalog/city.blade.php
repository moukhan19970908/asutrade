@extends('layouts.app')

@php
    $trail = [
        ['name' => 'Каталог', 'url' => route('catalog.index')],
        ['name' => $category->name, 'url' => route('catalog.category', $category)],
        ['name' => 'в '.$city['name_prepositional']],
    ];

    $heading = $category->name.' в '.$city['name_prepositional'];
    $pointsCount = count($city['points']);
@endphp

@section('title', $heading.' — купить в наличии | Asu Trade')
@section('description', $heading.': '.$total.' позиций в наличии на складе. '.$pointsCount.
    ($pointsCount === 1 ? ' магазин' : ' магазина').' Asu Trade в городе, самовывоз и доставка.')

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => $trail])
    @include('partials.schema-stores', ['onlyCity' => $citySlug])
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => $trail])

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $heading }}</h1>

    {{-- Уникальная часть страницы: реальные остатки склада этого города
         и адреса именно его точек. Ради неё страница и существует. --}}
    <p class="text-gray-600 mb-6 max-w-3xl">
        @if($total > 0)
            На складе в {{ $city['name_prepositional'] }} сейчас
            <strong>{{ $total }}</strong>
            @if($total % 10 === 1 && $total % 100 !== 11) позиция
            @elseif(in_array($total % 10, [2, 3, 4]) && ! in_array($total % 100, [12, 13, 14])) позиции
            @else позиций @endif
            из раздела «{{ $category->name }}».
        @else
            Сейчас в {{ $city['name_prepositional'] }} этих товаров нет в наличии.
        @endif
        Забрать можно в {{ $pointsCount === 1 ? 'магазине' : 'одном из магазинов' }}
        <a href="{{ route('stores.show', $citySlug) }}" class="text-blue-600 hover:underline">в {{ $city['name_prepositional'] }}</a>,
        доставка — по всему Казахстану.
    </p>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8 text-sm">
        <div class="font-semibold text-gray-900 mb-2">Магазины в {{ $city['name_prepositional'] }}</div>
        <ul class="space-y-1 text-gray-700">
            @foreach($city['points'] as $point)
                <li>
                    {{ $point['street'] }} —
                    <a href="{{ $point['map'] }}" target="_blank" rel="noopener noreferrer"
                       class="text-blue-600 hover:underline">карта</a>
                </li>
            @endforeach
        </ul>
        <div class="mt-2 text-gray-700">
            Телефон: <a href="tel:{{ config('stores.phone') }}" class="text-blue-600 hover:underline">{{ config('stores.phone_display') }}</a>
        </div>
    </div>

    @if($brands->isNotEmpty())
        <div class="mb-6">
            <div class="text-sm font-medium text-gray-700 mb-2">Бренды в наличии</div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('catalog.city', ['category' => $category, 'city' => $citySlug]) }}"
                   class="px-3 py-1 rounded-full text-sm {{ empty($activeBrand) ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    Все
                </a>
                @foreach($brands as $brand)
                    <a href="{{ route('catalog.city', ['category' => $category, 'city' => $citySlug, 'brand' => $brand->slug]) }}"
                       class="px-3 py-1 rounded-full text-sm {{ optional($activeBrand)->id === $brand->id ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                        {{ $brand->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($products->isEmpty())
        <p class="text-gray-500 mb-8">
            Нужной позиции нет на складе города — посмотрите
            <a href="{{ route('catalog.category', $category) }}" class="text-blue-600 hover:underline">весь раздел «{{ $category->name }}»</a>
            или напишите нам в
            <a href="{{ config('stores.whatsapp') }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">WhatsApp</a>,
            привезём под заказ.
        </p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
                <a href="{{ route('catalog.show', $product) }}"
                   class="block bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-32 object-contain mb-3">
                    @else
                        <div class="w-full h-32 bg-gray-100 rounded flex items-center justify-center mb-3">
                            <i class="fas fa-box text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    @if($product->brand)
                        <div class="text-gray-500 text-xs mb-1">{{ $product->brand->name }}</div>
                    @endif
                    <div class="font-semibold text-gray-900 text-sm mb-2">{{ $product->name }}</div>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-900">{{ number_format($product->price) }} ₸</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            В {{ $city['name_prepositional'] }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    @endif

    {{-- Перелинковка: спица → другие спицы и обратно в хаб --}}
    @if($otherCities->isNotEmpty())
        <div class="mt-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">{{ $category->name }} в других городах</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($otherCities as $other)
                    <a href="{{ route('catalog.city', ['category' => $category, 'city' => $other['slug']]) }}"
                       class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm">
                        в {{ $other['prepositional'] }} <span class="text-gray-400">({{ $other['count'] }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($otherCategories->isNotEmpty())
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Другие разделы в {{ $city['name_prepositional'] }}</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($otherCategories as $row)
                    <a href="{{ route('catalog.city', ['category' => $row['category'], 'city' => $citySlug]) }}"
                       class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm">
                        {{ $row['category']->name }} <span class="text-gray-400">({{ $row['count'] }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
