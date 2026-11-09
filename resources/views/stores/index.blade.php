@extends('layouts.app')

@php($trail = [['name' => 'Магазины']])

@section('title', 'Магазины Asu Trade в Актау, Жанаозене и Актобе')
@section('description', 'Адреса магазинов автозапчастей Asu Trade: три точки в Актау, магазины в Жанаозене и Актобе. Телефон и карты проезда.')

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => $trail])
    @include('partials.schema-stores')
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => $trail])

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Магазины Asu Trade</h1>
    <p class="text-gray-600 mb-8 max-w-3xl">
        {{ collect($cities)->sum(fn ($city) => count($city['points'])) }} магазина в
        {{ count($cities) }} городах Казахстана. Телефон единый:
        <a href="tel:{{ config('stores.phone') }}" class="text-blue-600 hover:underline">{{ config('stores.phone_display') }}</a>.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($cities as $slug => $city)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">
                    <a href="{{ route('stores.show', $slug) }}" class="hover:text-blue-600">{{ $city['name'] }}</a>
                </h2>
                <ul class="space-y-2 text-gray-600 text-sm">
                    @foreach($city['points'] as $point)
                        <li>
                            <a href="{{ $point['map'] }}" target="_blank" rel="noopener noreferrer"
                               class="hover:text-blue-600">{{ $point['street'] }}</a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('stores.show', $slug) }}" class="inline-block mt-4 text-blue-600 hover:underline text-sm">
                    Магазины в {{ $city['name_prepositional'] }} →
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
