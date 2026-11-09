@extends('layouts.app')

@section('title', 'Страница не найдена — Asu Trade')
@section('description', 'Такой страницы на сайте нет. Перейдите в каталог автозапчастей Asu Trade или воспользуйтесь поиском.')

@php($metaRobots = 'noindex, follow')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto text-center">
        <div class="text-6xl font-bold text-gray-300 mb-4">404</div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Страница не найдена</h1>
        <p class="text-gray-600 mb-8">
            Возможно, товар сняли с продажи или ссылка устарела.
            Посмотрите каталог — нужная позиция почти наверняка там.
        </p>

        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <a href="{{ route('catalog.index') }}"
               class="py-3 px-6 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                В каталог
            </a>
            <a href="{{ route('home') }}"
               class="py-3 px-6 bg-gray-100 text-gray-800 font-semibold rounded-md hover:bg-gray-200 transition-colors duration-200">
                На главную
            </a>
        </div>

        <div class="text-sm text-gray-500">
            <div class="mb-2 font-medium text-gray-700">Популярные разделы</div>
            <div class="flex flex-wrap justify-center gap-2">
                <a href="{{ route('brands.index') }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full">Бренды</a>
                <a href="{{ route('stores.index') }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full">Магазины</a>
                <a href="{{ route('delivery') }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full">Доставка</a>
                <a href="{{ route('support') }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full">Техподдержка</a>
            </div>
        </div>
    </div>
</div>
@endsection
