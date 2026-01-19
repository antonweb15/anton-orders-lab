<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SupplierProductController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/orders', [OrderController::class, 'apiIndex']);
Route::get('/orders/filtered', [OrderController::class, 'apiFiltered']);
Route::get('/orders/search', [OrderController::class, 'apiFiltered']);

Route::get('/test-api', [TestController::class, 'api']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\SupplierOrderController;

Route::prefix('supplier')->group(function () {
    Route::get('/products', [SupplierProductController::class, 'index']);
    Route::post('/orders', [SupplierOrderController::class, 'store']); // API to receive orders
});
