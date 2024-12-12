<?php

use Illuminate\Http\Request;

use Modules\StoreIntegrations\Http\Controllers\Api\ProductController;
use Modules\StoreIntegrations\Http\Controllers\StoreIntegrationsController;

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

Route::middleware('auth:api')->get('/storeintegrations', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'language', 'auth:dropshipper'], function () {
    Route::name('api.')->group(function () {
        Route::post('/storeintegrations/salla/sync', [StoreIntegrationsController::class, 'sallaSyncProducts'])->name('salla.syncProducts');

        Route::get('/storeintegrations/salla/products', [ProductController::class, 'list'])->name('salla.products.list');
        Route::post('storeintegrations/salla/product/update', [ProductController::class, 'updateSellingPrice'])->name('salla.products.updateSellingPrice');
        
        // get current connected stores
        Route::get('/storeintegrations/stores', [StoreIntegrationsController::class, 'getConnectedStores'])->name('stores.getConnectedStores');
    });
});
