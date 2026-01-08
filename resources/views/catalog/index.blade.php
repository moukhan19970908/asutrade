@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
@section('title', 'Каталог товаров - ASU Trade')

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .content {
        padding: 40px;
    }

    /* Стрелки в центре */
    .arrows-middle {
        position: fixed;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .arrow {
        width: 45px;
        height: 45px;
        background: #333;
        color: #fff;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 50%;
        opacity: 0.7;
    }

    .arrow:hover {
        opacity: 1;
    }

    /* Стрелка снизу */
    .arrow-bottom {
        position: fixed;
        right: 20px;
        bottom: 20px;
        width: 50px;
        height: 50px;
        background: #593887;
        color: #fff;
        font-size: 24px;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
    }

</style>

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar with filters -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Фильтры</h3>
                <form action="{{ route('catalog.index') }}" method="GET">
                    <div class="space-y-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Поиск</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   id="search" name="search" value="{{ request('search') }}" placeholder="Поиск товаров...">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Категория</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    id="category" name="category">
                                <option value="">Все категории</option>
                                @php
                                    $selectedCategory = request('category');
                                    if ($selectedCategory instanceof \App\Models\Category) {
                                        $selectedCategory = $selectedCategory->id;
                                    }
                                @endphp

                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       id="in_stock" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">В наличии</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i> Применить фильтры
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products grid -->
        <div class="lg:w-3/4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    @if(isset($category))
                        {{ $category->name }}
                    @else
                        Каталог товаров
                    @endif
                </h1>
                <span class="text-gray-500 text-sm">{{ $products->total() }} товаров</span>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                            <!-- Изображение товара -->
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-48 shadow-yellow-50 object-contain">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 bg-gray-100">
                                <h3 class="font-semibold text-gray-900 mb-2 text-sm whitespace-nowrap overflow-hidden text-ellipsis">{{ $product->name }}</h3>
                                <div class="text-gray-500 text-xs mb-2">{{ $product->category->name }}</div>


                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-bold text-lg text-gray-900">{{ number_format($product->price) }} ₸</span>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    <a href="{{ route('catalog.show', $product) }}"
                                       class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200 text-sm">
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
                <div class="arrows-middle">
                    <div class="arrow arrow-up">▲</div>
                    <div class="arrow arrow-down">▼</div>
                </div>

                <div class="mt-8">
                    {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Товары не найдены</h3>
                    <p class="text-gray-600 mb-4">Попробуйте изменить параметры поиска</p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Сбросить фильтры
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const arrowUp = document.querySelector('.arrow-up');
        const arrowDown = document.querySelector('.arrow-down');

        function scrollUp() {
            window.scrollBy({ top: -window.innerHeight, behavior: 'smooth' });
        }

        function scrollDown() {
            window.scrollBy({ top: window.innerHeight, behavior: 'smooth' });
        }

        function updateArrows() {
            const scrollTop = window.scrollY;
            const winHeight = window.innerHeight;
            const docHeight = document.documentElement.scrollHeight;

            arrowUp.style.display = scrollTop <= 10 ? 'none' : 'flex';
            arrowDown.style.display =
                scrollTop + winHeight >= docHeight - 10 ? 'none' : 'flex';
        }

        arrowUp.addEventListener('click', scrollUp);
        arrowDown.addEventListener('click', scrollDown);

        window.addEventListener('scroll', updateArrows);
        updateArrows();
    });

</script>
