<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SupplierProductController;


use App\Http\Controllers\CatalogController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/orders', [OrderController::class, 'apiIndex']);
Route::get('/orders/filtered', [OrderController::class, 'apiFiltered']);
Route::get('/orders/search', [OrderController::class, 'apiFiltered']);
Route::post('/orders/{order}/export', [OrderController::class, 'export']);

Route::get('/catalog', [CatalogController::class, 'apiIndex']);
Route::post('/catalog/import', [CatalogController::class, 'apiImport']);
Route::post('/catalog/clear', [CatalogController::class, 'apiClear']);

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

use App\Http\Controllers\StripeController;

Route::post('/stripe/webhook', [StripeController::class, 'webhook']);

