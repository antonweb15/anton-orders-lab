<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\ChangeOrderPrices;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;


 //welcome page laravel
 /*
 Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/orders', [OrderController::class, 'index']);

Route::get('/orders-api', [OrderController::class, 'indexApiPage']);

Route::get('/test-web', [TestController::class, 'web']);
Route::get('/test-bug', [TestController::class, 'bug']);


