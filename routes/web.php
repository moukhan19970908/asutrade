<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;

// Маршруты импорта товаров в админке (с отдельным префиксом) - В НАЧАЛЕ ФАЙЛА
Route::prefix('admin/import')->name('admin.products.')->group(function () {
    Route::post('/products', [App\Http\Controllers\Admin\ProductImportController::class, 'import'])->name('import');
    Route::get('/products', [App\Http\Controllers\Admin\ProductImportController::class, 'showImportForm'])->name('import.form');
});

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Каталог
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/category/{category}', [CatalogController::class, 'category'])->name('catalog.category');

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
    Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show.locale');
    Route::get('/category/{category}', [CatalogController::class, 'category'])->name('catalog.category.locale');
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
