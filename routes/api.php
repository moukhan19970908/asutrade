<?php

use App\Http\Controllers\Api\OneCController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\WhatsAppController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => response()->json(['pong' => true]));

/*
|--------------------------------------------------------------------------
| Интеграция с 1С (AsuAutoV2)
|--------------------------------------------------------------------------
*/
Route::controller(OneCController::class)->group(function () {
    Route::get('/checkUser', 'checkUser')->name('api.checkUser');
    Route::post('/createUser', 'createUser')->name('api.createUser');
    Route::get('/getLevels', 'getLevels')->name('api.getLevels');
    Route::get('/getHistory', 'getHistory')->name('api.getHistory');
    Route::get('/getOilChangeHistory', 'getOilChangeHistory')->name('api.getOilChangeHistory');
    Route::get('/findClientByQr', 'findClientByQr')->name('api.findClientByQr');
    Route::post('/createCar', 'createCar')->name('api.createCar');
    Route::get('/getCars', 'getCars')->name('api.getCars');
    Route::post('/createOilChange', 'createOilChange')->name('api.createOilChange');
    Route::get('/getCarOilChanges', 'getCarOilChanges')->name('api.getCarOilChanges');
});

/*
|--------------------------------------------------------------------------
| WhatsApp (Green API)
|--------------------------------------------------------------------------
*/
Route::controller(WhatsAppController::class)->group(function () {
    Route::post('/sendWhatsapp', 'sendWhatsapp')->name('api.sendWhatsapp');
    Route::get('/checkWhatsapp', 'checkWhatsapp')->name('api.checkWhatsapp');
});

/*
|--------------------------------------------------------------------------
| Подтверждение номера кодом из WhatsApp
|--------------------------------------------------------------------------
*/
Route::controller(OtpController::class)->group(function () {
    Route::post('/sendOtp', 'sendOtp')->name('api.sendOtp');
    Route::post('/verifyOtp', 'verifyOtp')->name('api.verifyOtp');
});
