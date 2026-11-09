@extends('layouts.app')

@section('title', 'Акции и персональные скидки — Asu Trade')
@section('description', 'Персональные скидки клиентов Asu Trade: цены на масла, фильтры и автохимию с учётом вашей скидки.')

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => [['name' => 'Акции']]])
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => [['name' => 'Акции']]])

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Акции</h1>

    @guest
        <p class="text-gray-600 mb-6 max-w-3xl">
            Скидки в Asu Trade персональные — они закреплены за вашим аккаунтом.
            Войдите, чтобы увидеть свои цены.
        </p>
        <a href="{{ route('login') }}"
           class="inline-block py-3 px-6 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
            Войти
        </a>
    @else
        @if($products->isEmpty())
            <p class="text-gray-600 max-w-3xl">
                Сейчас персональных скидок по вашему аккаунту нет.
                Загляните в <a href="{{ route('catalog.index') }}" class="text-blue-600 hover:underline">каталог</a> —
                там актуальные цены и наличие.
            </p>
        @else
            <p class="text-gray-600 mb-6 max-w-3xl">
                Товары с вашей персональной скидкой — {{ $products->count() }} позиций.
            </p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                    @php($discount = $product->personalDiscounts->first())
                    <a href="{{ route('catalog.show', $product) }}"
                       class="block bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                 class="w-full h-32 object-contain mb-3" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-32 bg-gray-100 rounded flex items-center justify-center mb-3">
                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                            </div>
                        @endif

                        <div class="font-semibold text-gray-900 text-sm mb-2">{{ $product->name }}</div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-red-600">
                                {{ number_format($product->getDiscountedPrice(auth()->id())) }} ₸
                            </span>
                            <span class="text-sm text-gray-500 line-through">{{ number_format($product->price) }} ₸</span>
                            @if($discount)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                    -{{ (int) $discount->discount_percent }}%
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endguest
</div>
@endsection
