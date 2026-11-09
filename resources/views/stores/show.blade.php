@extends('layouts.app')

@php($trail = [
    ['name' => 'Магазины', 'url' => route('stores.index')],
    ['name' => $city['name']],
])

@section('title', 'Автозапчасти в '.$city['name_prepositional'].' — магазины Asu Trade')
@section('description', 'Магазины Asu Trade в '.$city['name_prepositional'].': масла, фильтры, антифриз и автохимия. '.count($city['points']).' точки, адреса и карты проезда.')

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => $trail])
    @include('partials.schema-stores', ['onlyCity' => $citySlug])
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => $trail])

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
        Автозапчасти в {{ $city['name_prepositional'] }}
    </h1>
    <p class="text-gray-600 mb-8 max-w-3xl">
        Масла, фильтры, антифриз, смазки и автохимия в наличии.
        Телефон: <a href="tel:{{ config('stores.phone') }}" class="text-blue-600 hover:underline">{{ config('stores.phone_display') }}</a>,
        <a href="{{ config('stores.whatsapp') }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">WhatsApp</a>.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        @foreach($city['points'] as $point)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="font-semibold text-gray-900 mb-2">{{ $city['name'] }}</div>
                <div class="text-gray-600 mb-4">{{ $point['street'] }}</div>
                <a href="{{ $point['map'] }}" target="_blank" rel="noopener noreferrer"
                   class="text-blue-600 hover:underline text-sm">Открыть на карте 2ГИС →</a>
            </div>
        @endforeach
    </div>

    @php($cityCategories = \App\Support\CityCatalog::categoriesWithStock($citySlug))

    @if($cityCategories->isNotEmpty())
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Что есть в наличии в {{ $city['name_prepositional'] }}</h2>
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach($cityCategories as $row)
                <a href="{{ route('catalog.city', ['category' => $row['category'], 'city' => $citySlug]) }}"
                   class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm">
                    {{ $row['category']->name }} <span class="text-gray-400">({{ $row['count'] }})</span>
                </a>
            @endforeach
        </div>
    @endif

    <a href="{{ route('catalog.index') }}"
       class="inline-block py-3 px-6 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
        Перейти в каталог
    </a>
</div>
@endsection
