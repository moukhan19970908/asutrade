@extends('layouts.app')

@section('title', 'Бренды автозапчастей и масел — Asu Trade')
@section('description', 'Все производители в каталоге Asu Trade: масла Castrol, Shell, Gazpromneft, Лукойл, фильтры MANN, NF, Yume, антифризы и автохимия. Актау, Жанаозен, Актобе.')

@push('schema')
    @include('partials.schema-breadcrumbs', ['trail' => [['name' => 'Бренды']]])
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['trail' => [['name' => 'Бренды']]])

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Бренды</h1>
    <p class="text-gray-600 mb-8 max-w-3xl">
        Производители масел, фильтров, антифризов и автохимии, представленные в магазинах Asu Trade.
    </p>

    @if($brands->isEmpty())
        <p class="text-gray-500">Бренды пока не добавлены.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($brands as $brand)
                <a href="{{ route('brands.show', $brand) }}"
                   class="block bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-4 text-center">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                             class="w-14 h-14 mx-auto mb-3 object-contain" loading="lazy" decoding="async">
                    @else
                        <div class="w-14 h-14 mx-auto mb-3 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-industry text-gray-400"></i>
                        </div>
                    @endif
                    <div class="font-semibold text-gray-900">{{ $brand->name }}</div>
                    <div class="text-gray-500 text-sm">{{ $brand->products_count }} товаров</div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
