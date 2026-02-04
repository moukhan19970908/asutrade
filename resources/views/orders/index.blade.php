@extends('layouts.app')

@section('title', 'Мои заказы - ASU Trade')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Мой аккаунт</h2>
                    <nav class="space-y-2">
                        <a href="{{ route('profile.index') }}"
                           class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-user mr-3"></i>
                            Профиль
                        </a>
                        <a href="{{ route('orders.index') }}"
                           class="flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-md">
                            <i class="fas fa-box mr-3"></i>
                            Заказы
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            Адреса
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-cog mr-3"></i>
                            Настройки
                        </a>
                    </nav>
                </div>
            </div>
            <div class="lg:w-3/4">
                <div class="flex items-center mb-6">
                    <i class="fas fa-list text-2xl text-gray-600 mr-3"></i>
                    <h1 class="text-3xl font-bold text-gray-900">Мои заказы</h1>
                </div>

                @if($orders->count() > 0)
                    <div class="space-y-6">
                        @foreach($orders as $order)
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                Заказ #{{ $order->order_number }}
                                            </h3>
                                            <span
                                                class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_color }}">
                                    {{ $order->status_text }}
                                </span>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Дата заказа:</span>
                                                <p>{{ $order->created_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                            <div>
                                                <span class="font-medium">Товаров:</span>
                                                <p>{{ $order->items->count() }} шт.</p>
                                            </div>
                                            <div>
                                                <span class="font-medium">Сумма:</span>
                                                <p class="font-bold text-blue-600">{{ number_format($order->total_amount) }}
                                                    ₸</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 md:mt-0 md:ml-6">
                                        <a href="{{ route('orders.show', $order) }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                                            <i class="fas fa-eye mr-2"></i> Подробнее
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">У вас пока нет заказов</h2>
                        <p class="text-gray-600 mb-6">Сделайте свой первый заказ в нашем каталоге</p>
                        <a href="{{ route('catalog.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-shopping-bag mr-2"></i> Перейти в каталог
                        </a>
                    </div>
                @endif
            </div>
        </div>
@endsection
