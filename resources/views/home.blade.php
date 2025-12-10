@extends('layouts.app')

@section('title', 'ASU Trade - Главная')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <section class="mb-8">
        <div class="rounded-lg overflow-hidden shadow-lg">
            <img src="{{ asset('img/banner.webp') }}" alt="Auto Parts Banner" class="w-full h-auto max-h-96 object-cover">
        </div>
    </section>

    <!-- Категории -->
    <section class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Категории</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('catalog.category', $category) }}" class="block">
                    <div class="bg-gray-100 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-4 text-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-24 h-24 mx-auto mb-2 object-contain">
                        @else
                            <div class="w-12 h-12 mx-auto mb-2 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-th-large text-gray-500"></i>
                            </div>
                        @endif
                        <div class="font-semibold text-gray-900 text-base">{{ $category->name }}</div>
                        <div class="text-gray-500 text-sm">{{ $category->products_count ?? 0 }} товаров</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Популярные товары -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Популярные товары</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($popularProducts as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                    <!-- Изображение товара -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-contain">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ $product->name }}</h3>
                        <div class="text-gray-500 text-xs mb-2">{{ $product->category->name }}</div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold text-lg text-gray-900">{{ number_format($product->price) }} ₸</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('catalog.show', $product) }}" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200 text-sm">
                                <i class="fas fa-eye mr-1"></i> Подробнее
                            </a>
                            @if($product->in_stock)
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm">
                                        <i class="fas fa-cart-plus mr-1"></i> В корзину
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-th-large mr-2"></i> Смотреть все товары
            </a>
        </div>
    </section>

    <!-- Новые поступления -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Новые поступления</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($newProducts as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                    <!-- Изображение товара -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-contain">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ $product->name }}</h3>
                        <div class="text-gray-500 text-xs mb-2">{{ $product->category->name }}</div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold text-lg text-gray-900">{{ number_format($product->price) }} ₸</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('catalog.show', $product) }}" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200 text-sm">
                                <i class="fas fa-eye mr-1"></i> Подробнее
                            </a>
                            @if($product->in_stock)
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm">
                                        <i class="fas fa-cart-plus mr-1"></i> В корзину
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Популярные бренды -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Популярные бренды</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($popularBrands as $brand)
                <div class="bg-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-4 text-center">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-10 h-10 mx-auto mb-2 object-contain">
                    @else
                        <div class="w-10 h-10 mx-auto mb-2 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-industry text-gray-500"></i>
                        </div>
                    @endif
                    <div class="font-semibold text-gray-900 text-sm">{{ $brand->name }}</div>
                    <div class="text-gray-500 text-xs">{{ Str::limit($brand->description, 40) }}</div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
