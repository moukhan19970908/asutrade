<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Asu Trade')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Asu Trade" class="h-10">
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('catalog.index') }}" class="text-gray-900 hover:text-gray-600">Каталог</a>
                    <a href="#" class="text-gray-900 hover:text-gray-600">Бренды</a>
                    <a href="#" class="text-gray-900 hover:text-gray-600">Акции</a>
                    <a href="#" class="text-gray-900 hover:text-gray-600">Доставка</a>
                </nav>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                        Корзина
                    </a>
                    <button class="text-gray-900 hover:text-gray-600">
                        <i class="fas fa-globe"></i>
                    </button>
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center hover:bg-gray-400 transition-colors">
                                <i class="fas fa-user text-gray-600"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                    Привет, {{ Auth::user()->name }}!
                                </div>
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Профиль
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-900 hover:text-gray-600">
                            <i class="fas fa-sign-in-alt"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Asu Trade</h3>
                    <p class="text-gray-300">Качественные автозапчасти для вашего автомобиля</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Каталог</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white">Запчасти</a></li>
                        <li><a href="#" class="hover:text-white">Масла</a></li>
                        <li><a href="#" class="hover:text-white">Аксессуары</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Информация</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white">О компании</a></li>
                        <li><a href="#" class="hover:text-white">Доставка</a></li>
                        <li><a href="#" class="hover:text-white">Контакты</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Контакты</h4>
                    <div class="space-y-2 text-gray-300">
                        <p>+7 (777) 123-45-67</p>
                        <p>info@asutrade.kz</p>
                        <p>Актау, ул. Примерная, 123</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; 2024 Asu Trade. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js for dropdown functionality -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html> 