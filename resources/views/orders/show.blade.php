@extends('layouts.app')

@section('title', 'Заказ #' . $order->order_number . ' - ASU Trade')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <i class="fas fa-receipt text-2xl text-gray-600 mr-3"></i>
        <h1 class="text-3xl font-bold text-gray-900">Заказ #{{ $order->order_number }}</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Детали заказа</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <span class="text-sm text-gray-500">Номер заказа:</span>
                        <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Дата заказа:</span>
                        <p class="font-semibold text-gray-900">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Статус:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_color }}">
                            {{ $order->status_text }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Сумма заказа:</span>
                        <p class="font-bold text-lg text-blue-600">{{ number_format($order->total_amount) }} ₸</p>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mb-6">
                        <span class="text-sm text-gray-500">Комментарий к заказу:</span>
                        <p class="text-gray-900 mt-1">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Товары в заказе</h3>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center py-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} шт. × {{ number_format($item->price) }} ₸</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-900">{{ number_format($item->subtotal) }} ₸</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Итого:</span>
                        <span class="text-blue-600">{{ number_format($order->total_amount) }} ₸</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Информация о доставке</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Получатель:</span>
                        <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-500">Телефон:</span>
                        <p class="font-medium text-gray-900">{{ $order->shipping_phone }}</p>
                    </div>
                    
                    @if($order->shipping_country)
                        <div>
                            <span class="text-sm text-gray-500">Страна:</span>
                            <p class="font-medium text-gray-900">{{ $order->shipping_country }}</p>
                        </div>
                    @endif
                    
                    @if($order->shipping_city)
                        <div>
                            <span class="text-sm text-gray-500">Город:</span>
                            <p class="font-medium text-gray-900">{{ $order->shipping_city }}</p>
                        </div>
                    @endif
                    
                    @if($order->shipping_address)
                        <div>
                            <span class="text-sm text-gray-500">Адрес:</span>
                            <p class="font-medium text-gray-900">{{ $order->shipping_address }}</p>
                        </div>
                    @endif
                    
                    @if($order->shipping_postal_code)
                        <div>
                            <span class="text-sm text-gray-500">Почтовый индекс:</span>
                            <p class="font-medium text-gray-900">{{ $order->shipping_postal_code }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-6 space-y-3">
                <a href="{{ route('orders.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-list mr-2"></i> Все заказы
                </a>
                <a href="{{ route('catalog.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-shopping-bag mr-2"></i> Продолжить покупки
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 