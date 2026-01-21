<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\ChangeOrderPrices;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SupplierCatalogController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\StripeController;


 //welcome page laravel
 /*
 Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders/{order}/export', [OrderController::class, 'export'])->name('orders.export');

Route::get('/orders-api', [OrderController::class, 'indexApiPage']);

Route::get('/test-web', [TestController::class, 'web']);
Route::get('/test-bug', [TestController::class, 'bug']);

use App\Http\Controllers\SupplierOrderController;

Route::get('/supplier/catalog', [SupplierCatalogController::class, 'index'])
    ->name('supplier.catalog');

Route::get('/supplier/orders', [SupplierOrderController::class, 'index'])
    ->name('supplier.orders.index');


Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::post('/catalog/import', [CatalogController::class, 'import'])->name('catalog.import');
Route::post('/catalog/clear', [CatalogController::class, 'clear'])->name('catalog.clear');

Route::get('/pay', fn () => view('pay'))->name('pay.index');
Route::post('/pay', [StripeController::class, 'pay'])
    ->name('stripe.pay');

Route::get('/success', fn () => view('success'))->name('payment.success');
Route::get('/cancel', fn () => view('cancel'))->name('payment.cancel');



