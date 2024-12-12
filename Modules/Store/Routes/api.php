<?php

use Illuminate\Http\Request;
use Modules\Store\Http\Controllers\StoreController;
use Modules\Store\Http\Controllers\ProductController;
use Modules\Store\Http\Controllers\OrderListenerController;
use Modules\Store\Http\Controllers\Api\ProductController as ApiProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Modules\Store\Http\Controllers\Api\OAuthController;
Route::middleware('auth:api')->get('/store', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'api', 'language', 'auth:dropshipper'], function () {
    Route::name('api.')->group(function () {
        Route::prefix('/store')->name('store.')->group(function () {
            Route::get('redirect', [OAuthController::class, 'redirect'])->name('redirect');
            Route::get('callback', [OAuthController::class, 'callback'])->name('callback');

            Route::prefix('/product')->name('product.')->group(function () {
                Route::any('/list', [ApiProductController::class, 'list'])->name('list');
                Route::post('/addProduct', [ApiProductController::class, 'addProduct']);
                Route::post('/addProducts', [ApiProductController::class, 'addProducts']);
                Route::delete('/deletedProduct', [ApiProductController::class, 'deletedProduct']);

            });
        });
   
    });
});
Route::post('/store/step-one', [StoreController::class, 'stepOne']);
Route::post('/store/step-three', [StoreController::class, 'stepThree']);
Route::post('/store/register', [StoreController::class, 'register']);
Route::post('/store/seedDatabase', [StoreController::class, 'seedDatabase']);

Route::post('/store/export/products', [ProductController::class, 'export']);
//todo change
Route::post('/store/order/create', [OrderListenerController::class, 'orderCreated']);
Route::post('/store/order/updated', [OrderListenerController::class, 'orderUpdated']);
