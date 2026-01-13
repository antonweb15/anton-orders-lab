<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/orders', [OrderController::class, 'apiIndex']);

Route::middleware(['change.order.prices.api'])->group(function () {
    Route::get('/orders', [OrderController::class, 'apiIndex']);
});

Route::get('/test-api', [TestController::class, 'api']);
