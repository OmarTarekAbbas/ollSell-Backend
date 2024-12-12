<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterCatalog\Http\Controllers\Api\EventController;
use Modules\MasterCatalog\Http\Controllers\Api\ProductController;
use Modules\MasterCatalog\Http\Controllers\Api\BundleController;
use Modules\MasterCatalog\Http\Controllers\Api\ProfitController;
use Modules\MasterCatalog\Http\Controllers\Api\FavoriteController;
use Modules\MasterCatalog\Http\Controllers\Api\SearchController;

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
Route::group(['middleware' => 'api', 'language', 'auth:dropshipper'], function () {
    Route::name('api.')->group(function () {
        Route::prefix('/master_catalog')->name('master_catalog.')->group(function () {
            //MasterCatalog
            
            Route::any('/search', [SearchController::class, 'index']);

            Route::prefix('/product')->name('product.')->group(function () {
                Route::any('/list', [ProductController::class, 'list'])->name('list');
                Route::any('/recentl/list', [ProductController::class, 'recentlList'])->name('recentl.list');
                Route::any('/category/list', [ProductController::class, 'categoryProductList'])->name('category.list');
                Route::get('/product/{id}', [ProductController::class, 'show']);
                Route::post('/downloadMedia', [ProductController::class, 'downloadMediaZip']);
            });  
            //bundles
            Route::prefix('/bundles')->name('bundle.')->group(function () {
                Route::any('/', [BundleController::class, 'list'])->name('list');
                Route::get('/{id}', [BundleController::class, 'show']);
            });

            Route::get('/bundles', [BundleController::class, 'list']); // Add this line

            //profit
            Route::prefix('/profit')->name('master_catalog.')->group(function () {
                Route::post('/update', [ProfitController::class, 'update']);
                Route::post('/remove', [ProfitController::class, 'remove']);
            });
            //favorite
            Route::prefix('/favorite')->name('master_catalog.')->group(function () {
                // list records
                Route::get('list', [FavoriteController::class, 'list']);

                Route::get('my-product', [FavoriteController::class, 'index']);
                // one record
                Route::get('show/{id}', [FavoriteController::class, 'show']);
                // add to favorites
                Route::post('/add', [FavoriteController::class, 'add']);
                //remove from favorites
                Route::post('/remove', [FavoriteController::class, 'remove']);
                //export from favorites
                Route::get('/export', [FavoriteController::class, 'export']);
            });
            //Events
            Route::prefix('/event')->group(function () {
                // list records
                Route::get('list', [EventController::class, 'list']);
                // one record
                Route::get('show/{id}', [EventController::class, 'show']);
            });

            Route::post('/products', [ProductController::class, 'categoryProducts']);

        });
    });
});
