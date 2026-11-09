@extends('layouts.app')

@php
    $trail = [
        ['name' => 'Бренды', 'url' => route('brands.index')],
        ['name' => $brand->name],
    ];
@endphp

@section('title', $brand->name.' — купить в Актау, Жанаозене и Актобе | Asu Trade')
@section('description', \Illuminate\Support\Str::limit(
    $brand->description ?: 'Продукция '.$brand->name.' в наличии в магазинах Asu Trade: Актау, Жанаозен, Актобе. Доставка по Казахстану.',
    160))

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => $trail])
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => $trail])

    <div class="flex items-center gap-4 mb-2">
        @if($brand->logo)
            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-16 h-16 object-contain" loading="lazy" decoding="async">
        @endif
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $brand->name }}</h1>
    </div>

    @if($brand->description)
        <p class="text-gray-600 mb-6 max-w-3xl">{{ $brand->description }}</p>
    @endif

    @if($categories->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Категории {{ $brand->name }}</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $category)
                    <a href="{{ route('catalog.category', $category) }}"
                       class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm">
                        {{ $category->name }} <span class="text-gray-400">({{ $category->products_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($products->isEmpty())
        <p class="text-gray-500">Товаров этого бренда сейчас нет в каталоге.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
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
                    @if($product->category)
                        <div class="text-gray-500 text-xs mb-1">{{ $product->category->name }}</div>
                    @endif
                    <div class="font-semibold text-gray-900 text-sm mb-2">{{ $product->name }}</div>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-900">{{ number_format($product->price) }} ₸</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->in_stock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->in_stock ? 'В наличии' : 'Нет в наличии' }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
