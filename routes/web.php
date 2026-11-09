<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CityCatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegacyUrlController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\MobileAppController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupportController;

// Маршруты импорта товаров в админке (с отдельным префиксом) - В НАЧАЛЕ ФАЙЛА
Route::prefix('admin/import')->name('admin.products.')->group(function () {
    Route::post('/products', [App\Http\Controllers\Admin\ProductImportController::class, 'import'])->name('import');
    Route::get('/products', [App\Http\Controllers\Admin\ProductImportController::class, 'showImportForm'])->name('import.form');
});

// Главная страница
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
// Краткая карта сайта для языковых моделей (llmstxt.org).
Route::get('/llms.txt', [LlmsTxtController::class, 'index'])->name('llms');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/delivery', [HomeController::class, 'delivery'])->name('delivery');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/user-agreement', [HomeController::class, 'userAgreement'])->name('user-agreement');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');

// Техподдержка
Route::get('/support', [SupportController::class, 'index'])->name('support');
Route::post('/support', [SupportController::class, 'store'])->name('support.store')->middleware('throttle:5,1');
// Каталог
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

// Старый адрес товара /catalog/123. Объявлен до /catalog/{category},
// иначе числовой id попытается найтись среди слагов категорий.
Route::get('/catalog/{product}', [LegacyUrlController::class, 'product'])
    ->whereNumber('product')
    ->name('legacy.product');

Route::get('/catalog/{category}', [CatalogController::class, 'category'])->name('catalog.category');
// Категория в городе: опирается на остатки склада этого города.
Route::get('/catalog/{category}/{city}', [CityCatalogController::class, 'show'])->name('catalog.city');

Route::get('/product/{product}', [CatalogController::class, 'show'])->name('catalog.show');

// Старый адрес категории /category/5.
Route::get('/category/{category}', [LegacyUrlController::class, 'category'])
    ->whereNumber('category')
    ->name('legacy.category');

// Бренды: вторая ось каталога рядом с категориями.
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');

Route::get('/akcii', [PromotionController::class, 'index'])->name('promotions.index');

// Магазины по городам: локальный поиск («автозапчасти Актау»).
Route::get('/magaziny', [StoreController::class, 'index'])->name('stores.index');
Route::get('/magaziny/{city}', [StoreController::class, 'show'])->name('stores.show');

// Мобильное приложение: инструкция по платформе и отдача APK
// с правильным Content-Type.
Route::get('/prilozhenie', [MobileAppController::class, 'index'])->name('mobile-app.index');
Route::get('/download/asuauto.apk', [MobileAppController::class, 'download'])->name('mobile-app.download');
Route::redirect('/app', '/prilozhenie', 301);

// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Локализованные маршруты
Route::prefix('{locale}')->where(['locale' => 'ru|kz'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.locale');
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index.locale');
    Route::get('/catalog/{category}', [CatalogController::class, 'category'])->name('catalog.category.locale');
    Route::get('/product/{product}', [CatalogController::class, 'show'])->name('catalog.show.locale');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index.locale');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add.locale');
    Route::put('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update.locale');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove.locale');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear.locale');
});

// Маршруты авторизации
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты профиля (требуют авторизации)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Маршруты заказов (требуют авторизации)
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
