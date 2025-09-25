@extends('layouts.app')

@section('title', $product->name . ' - ASU Trade')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('catalog.index') }}" class="hover:text-blue-600">Каталог</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('catalog.category', $product->category) }}" class="hover:text-blue-600">{{ $product->category->name }}</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Product Image -->
        <div class="lg:w-1/2">
            <div class="bg-white rounded-lg shadow-md p-6">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-auto rounded-lg" alt="{{ $product->name }}">
                @else
                    <div class="bg-gray-100 rounded-lg py-12 text-center">
                        <i class="fas fa-image text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500">Изображение отсутствует</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="lg:w-1/2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <div class="flex items-center space-x-2 mb-4">
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $product->category->name }}</span>
                    <span class="px-3 py-1 rounded-full text-sm {{ $stockInfo['available'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $stockInfo['available'] ? 'В наличии' : 'Нет в наличии' }}
                    </span>
                </div>

                @if($product->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Описание</h3>
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center mb-6">
                        @if($stockInfo['type'] === 'user_warehouse')
                            @if($discountInfo['has_discount'])
                                <!-- Цена со скидкой -->
                                <div class="flex flex-col">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-3xl font-bold text-red-600">{{ number_format($discountInfo['discounted_price']) }} ₸</span>
                                        <span class="text-lg text-gray-500 line-through">{{ number_format($discountInfo['original_price']) }} ₸</span>
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                            -{{ $discountInfo['discount_percent'] }}%
                                        </span>
                                    </div>

                                </div>
                            @else
                                <!-- Обычная цена -->
                                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($product->price) }} ₸</h3>
                            @endif
                        @endif
                    </div>

                    @if($stockInfo['available'])
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Количество</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           id="quantity" name="quantity" value="1" min="1"
                                           max="{{ $stockInfo['type'] === 'user_warehouse' ? $stockInfo['stock'] : $stockInfo['total_stock'] }}">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                                    <button type="submit" class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-cart-plus mr-2"></i> В корзину
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                <span class="text-yellow-800">Товар временно отсутствует</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Характеристики</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Категория:</span>
                        <span class="text-gray-900">{{ $product->category->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Артикул:</span>
                        <span class="text-gray-900">{{ $product->id }}</span>
                    </div>

                    <!-- Остатки товара -->
                    <div class="py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Остатки:</span>
                        <div class="mt-2">
                            @if($stockInfo['type'] === 'user_warehouse')
                                <!-- Авторизованный пользователь -->
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-900">{{ $stockInfo['warehouse_name'] }}:</span>
                                    <span class="font-semibold {{ $stockInfo['available'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $stockInfo['stock'] }} шт.
                                    </span>
                                </div>
                            @else
                                <!-- Неавторизованный пользователь -->
                                @foreach($stockInfo['warehouses'] as $warehouse)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-900">{{ $warehouse['warehouse_name'] }}:</span>
                                        <span class="font-semibold {{ $warehouse['stock'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $warehouse['stock'] }} шт.
                                        </span>
                                    </div>
                                @endforeach
                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-200">
                                    <span class="font-medium text-gray-900">Общий остаток:</span>
                                    <span class="font-bold {{ $stockInfo['total_stock'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $stockInfo['total_stock'] }} шт.
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="font-medium text-gray-700">Наличие:</span>
                        <span class="text-gray-900">{{ $stockInfo['available'] ? 'В наличии' : 'Нет в наличии' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Похожие товары</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                <!-- Изображение товара -->
                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
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

<section class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Популярные товары</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($popularProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                <!-- Изображение товара -->
                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
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
@endsection
