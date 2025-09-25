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
                <a href="{{route('about')}}" class="text-gray-900 hover:text-gray-600">О нас</a>
                <a href="#" class="text-gray-900 hover:text-gray-600">Акции</a>
                <a href="{{route('delivery')}}" class="text-gray-900 hover:text-gray-600">Доставка</a>
            </nav>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}"
                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                    Корзина
                </a>
                <button class="text-gray-900 hover:text-gray-600">
                    <i class="fas fa-globe"></i>
                </button>
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center hover:bg-gray-400 transition-colors">
                            <i class="fas fa-user text-gray-600"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                Привет, {{ Auth::user()->name }}!
                            </div>
                            <a href="{{ route('profile.index') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Профиль
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
                <h4 class="text-md font-semibold mb-4">Адреса магазинов</h4>
                <div class="space-y-4">
                    <div><p class="font-medium">г. Актау:</p><a href="https://go.2gis.com/8ETIC" target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="hover:text-yellow-400 transition-colors">БЦ
                            Кулагер 25-й микрорайон (2GIS карта)</a><br><a href="https://go.2gis.com/bxc7n"
                                                                           target="_blank" rel="noopener noreferrer"
                                                                           class="hover:text-yellow-400 transition-colors">Микрорайн
                            6a,20/1 (2GIS карта)</a><br><a href="https://go.2gis.com/740Gn" target="_blank"
                                                           rel="noopener noreferrer"
                                                           class="hover:text-yellow-400 transition-colors">Микрорайон
                            28a, 30/22 (2GIS карта)</a></div>
                    <div><p class="font-medium">г.Жанаозен:</p><a href="https://go.2gis.com/790d9" target="_blank"
                                                                  rel="noopener noreferrer"
                                                                  class="hover:text-yellow-400 transition-colors">​Улица
                            Кайсагалиев Гарипулла, 8/1 (2GIS карта)</a></div>
                    <div><p class="font-medium">г. Актобе:</p><a href="https://go.2gis.com/npUsZ" target="_blank"
                                                                 rel="noopener noreferrer"
                                                                 class="hover:text-yellow-400 transition-colors">Проспект
                            Кенес Нокина, 3д (2GIS карта)</a></div>
                </div>
            </div>
            <div>
                <h4 class="text-md font-semibold mb-4">Информация</h4>
                <ul class="space-y-2 text-gray-300">
                    <li><a href="{{route('about')}}" class="hover:text-white">О компании</a></li>
                    <li><a href="{{route('delivery')}}" class="hover:text-white">Доставка</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-md font-semibold mb-4">Контакты</h4>
                <div class="space-y-2 text-gray-300">
                    <p>+7 (707) 436 02 15</p>
                    <p class="flex items-center">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512"
                             class="mr-2" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
                        </svg>
                        <a href="https://wa.me/77788084030" target="_blank" rel="noopener noreferrer"
                           class="hover:text-yellow-400 transition-colors">WhatsApp</a></p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
            <p>&copy; 2025 Asu Trade. Все права защищены.</p>
        </div>
    </div>
</footer>

<!-- Alpine.js for dropdown functionality -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
