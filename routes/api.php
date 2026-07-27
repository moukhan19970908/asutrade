<?php

use App\Http\Controllers\Api\OneCController;
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
    Route::get('/findClientByQr', 'findClientByQr')->name('api.findClientByQr');
    Route::post('/createCar', 'createCar')->name('api.createCar');
    Route::post('/createOilChange', 'createOilChange')->name('api.createOilChange');
});
