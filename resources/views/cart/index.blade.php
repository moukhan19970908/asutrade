@extends('layouts.app')

@section('title', 'Корзина - ASU Trade')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <i class="fas fa-shopping-cart text-2xl text-gray-600 mr-3"></i>
        <h1 class="text-3xl font-bold text-gray-900">Корзина</h1>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if($cart->items->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Товары в корзине ({{ $cart->items_count }})</h2>
                    </div>
                    <div class="p-6">
                        @foreach($cart->items as $item)
                            <div class="flex items-center py-4 border-b border-gray-100 last:border-b-0">
                                <!-- Product Image -->
                                <div class="w-20 h-20 mr-4">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             class="w-full h-full object-cover rounded-lg" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Info -->
                                <div class="flex-1 mr-4">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                    <p class="text-gray-500 text-sm">{{ $item->product->category->name }}</p>
                                    
                                    @if(isset($discountInfo[$item->id]) && $discountInfo[$item->id]['has_discount'])
                                        <div class="mt-2">
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                                Персональная скидка -{{ $discountInfo[$item->id]['discount_percent'] }}%
                                            </span>
                                            @if($discountInfo[$item->id]['description'])
                                                <p class="text-xs text-gray-600 mt-1">{{ $discountInfo[$item->id]['description'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Price -->
                                <div class="w-24 text-right mr-4">
                                    @if(isset($discountInfo[$item->id]) && $discountInfo[$item->id]['has_discount'])
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-red-600">{{ number_format($discountInfo[$item->id]['discounted_price']) }} ₸</span>
                                            <span class="text-sm text-gray-500 line-through">{{ number_format($discountInfo[$item->id]['original_price']) }} ₸</span>
                                        </div>
                                    @else
                                        <span class="font-semibold text-gray-900">{{ number_format($item->product->price) }} ₸</span>
                                    @endif
                                </div>
                                
                                <!-- Quantity -->
                                <div class="flex items-center mr-4">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" class="w-16 px-2 py-1 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               name="quantity" value="{{ $item->quantity }}" min="1" max="99">
                                        <button type="submit" class="ml-2 p-1 text-blue-600 hover:text-blue-800 transition-colors">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Subtotal -->
                                <div class="w-24 text-right mr-4">
                                    @if(isset($discountInfo[$item->id]) && $discountInfo[$item->id]['has_discount'])
                                        <div class="flex flex-col items-end">
                                            <span class="font-bold text-red-600">{{ number_format($discountInfo[$item->id]['total_discounted']) }} ₸</span>
                                            <span class="text-sm text-gray-500 line-through">{{ number_format($discountInfo[$item->id]['total_original']) }} ₸</span>
                                        </div>
                                    @else
                                        <span class="font-bold text-gray-900">{{ number_format($item->subtotal) }} ₸</span>
                                    @endif
                                </div>
                                
                                <!-- Remove Button -->
                                <div>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 transition-colors" 
                                                onclick="return confirm('Удалить товар из корзины?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Продолжить покупки
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 text-red-700 font-medium rounded-md hover:bg-red-50 transition-colors duration-200" 
                                onclick="return confirm('Очистить корзину?')">
                            <i class="fas fa-trash mr-2"></i> Очистить корзину
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Итого</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Товаров:</span>
                            <span class="font-medium">{{ $cart->items_count }}</span>
                        </div>
                        
                        @php
                            $totalOriginal = 0;
                            $totalDiscounted = 0;
                            $totalSaved = 0;
                            
                            foreach($cart->items as $item) {
                                if(isset($discountInfo[$item->id])) {
                                    $totalOriginal += $discountInfo[$item->id]['total_original'];
                                    $totalDiscounted += $discountInfo[$item->id]['total_discounted'];
                                    $totalSaved += $discountInfo[$item->id]['saved_amount'];
                                } else {
                                    $totalOriginal += $item->subtotal;
                                    $totalDiscounted += $item->subtotal;
                                }
                            }
                        @endphp
                        
                        @if($totalSaved > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Экономия:</span>
                                <span class="font-semibold">-{{ number_format($totalSaved) }} ₸</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between text-lg font-bold">
                            <span>Итого:</span>
                            @if($totalSaved > 0)
                                <div class="flex flex-col items-end">
                                    <span class="text-red-600">{{ number_format($totalDiscounted) }} ₸</span>
                                    <span class="text-sm text-gray-500 line-through">{{ number_format($totalOriginal) }} ₸</span>
                                </div>
                            @else
                                <span class="text-blue-600">{{ number_format($totalDiscounted) }} ₸</span>
                            @endif
                        </div>
                    </div>
                    
                    @auth
                        <a href="{{ route('orders.checkout') }}" class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200 inline-flex items-center justify-center">
                            <i class="fas fa-credit-card mr-2"></i> Оформить заказ
                        </a>
                        <p class="text-sm text-gray-500 mt-2 text-center">
                            Вы авторизованы как: {{ Auth::user()->name }}
                        </p>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Войти для оформления
                            </a>
                            <p class="text-sm text-gray-500 text-center">
                                Авторизуйтесь для оформления заказа
                            </p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Корзина пуста</h2>
            <p class="text-gray-600 mb-6">Добавьте товары в корзину, чтобы продолжить покупки</p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-shopping-bag mr-2"></i> Перейти в каталог
            </a>
        </div>
    @endif
</div>
@endsection 