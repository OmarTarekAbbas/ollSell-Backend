<?php

use Illuminate\Http\Request;
use Modules\Wms\Http\Controllers\WmsController;

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
//todo change
Route::middleware('auth:api')->get('/wms', function (Request $request) {
    return $request->user();
});

Route::get('/wms/products', [WmsController::class, 'fetchProducts']);
Route::get('/wms/catalog/master', [WmsController::class, 'fetchMasterCatalog']);
Route::get('/wms/products/{sku_code}', [WmsController::class, 'fetchProductBySKU']);
Route::post('/wms/products', [WmsController::class, 'createProduct']);
Route::put('/wms/products/{sku_code}', [WmsController::class, 'updateProduct']);
Route::get('/wms/inventory/hub', [WmsController::class, 'fetchHubInventory']);
Route::post('/wms/orders', [WmsController::class, 'createOrder']);
