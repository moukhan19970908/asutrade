@extends('layouts.app')

@section('title', 'Профиль - Asu Trade')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Sidebar -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Мой аккаунт</h2>
                <nav class="space-y-2">
                    <a href="#" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-home mr-3"></i>
                        Обзор
                    </a>
                    <a href="{{ route('profile.index') }}" class="flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-md">
                        <i class="fas fa-user mr-3"></i>
                        Профиль
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">
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

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Профиль</h1>
                    <p class="text-gray-600 mt-2">Управляйте вашей личной информацией и настройками аккаунта.</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Личная информация</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Номер телефона</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors duration-200">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 