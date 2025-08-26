@extends('layouts.app')

@section('title', 'Оформление заказа - ASU Trade')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <i class="fas fa-credit-card text-2xl text-gray-600 mr-3"></i>
        <h1 class="text-3xl font-bold text-gray-900">Оформление заказа</h1>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Order Form -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Данные доставки</h2>
                
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-2">Имя получателя *</label>
                            <input type="text" id="shipping_name" name="shipping_name" 
                                   value="{{ old('shipping_name', $user->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_name') border-red-500 @enderror">
                            @error('shipping_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-2">Телефон *</label>
                            <input type="text" id="shipping_phone" name="shipping_phone" 
                                   value="{{ old('shipping_phone', $user->phone) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_phone') border-red-500 @enderror">
                            @error('shipping_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-2">Страна</label>
                            <input type="text" id="shipping_country" name="shipping_country" 
                                   value="{{ old('shipping_country', $user->country) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_country') border-red-500 @enderror">
                            @error('shipping_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">Город</label>
                            <input type="text" id="shipping_city" name="shipping_city" 
                                   value="{{ old('shipping_city', $user->city) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Адрес доставки</label>
                        <input type="text" id="shipping_address" name="shipping_address" 
                               value="{{ old('shipping_address', $user->address) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_address') border-red-500 @enderror">
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-2">Почтовый индекс</label>
                        <input type="text" id="shipping_postal_code" name="shipping_postal_code" 
                               value="{{ old('shipping_postal_code', $user->postal_code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shipping_postal_code') border-red-500 @enderror">
                        @error('shipping_postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Комментарий к заказу</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                  placeholder="Дополнительная информация о заказе...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Вернуться в корзину
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i> Оформить заказ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ваш заказ</h3>
                
                <div class="space-y-3 mb-6">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} шт.</p>
                            </div>
                            <span class="font-semibold text-gray-900">{{ number_format($item->subtotal) }} ₸</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Итого:</span>
                        <span class="text-blue-600">{{ number_format($cart->total) }} ₸</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 