<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\ChangeOrderPrices;
use App\Http\Controllers\HomeController;


 //welcome page laravel
 /*
 Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/orders', [OrderController::class, 'index'])
    ->middleware(ChangeOrderPrices::class);

Route::get('/orders-api', [OrderController::class, 'indexApiPage']);

