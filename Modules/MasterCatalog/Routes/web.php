<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterCatalog\Http\Controllers\EventController;
use Modules\MasterCatalog\Http\Controllers\AttributeController;
use Modules\MasterCatalog\Http\Controllers\ProductController;
use Modules\MasterCatalog\Http\Controllers\WarehouseController;
use Modules\MasterCatalog\Http\Controllers\BundleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//todo change
Route::group(['middleware' => 'admin', 'auth'], function () {
    Route::prefix('master_catalog')->group(function () {

        Route::controller(ProductController::class)->prefix('/product')->name('product.')->group(function () {
            Route::post('/get-commission', 'getCommissionCategory')->name('getCommissionCategory');

            Route::get('/importfile', 'importFile')->name('importfile');
            Route::post('/import', 'import')->name('import');
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');

            Route::get('/search', 'search')->name('search');
            Route::get('/inBundleSearch', 'inBundleSearch')->name('inBundleSearch');

            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::get('/{id}/scan', 'scanQuantityWms')->name('scan');

            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/downloadSample', 'getDownload')->name('getDownload');
            Route::get('/files/download/', 'download')->name('files.download');
            Route::get('/delete_iamge', 'deleteImage')->name('delete_image');
            Route::get('/check_uploading_status', 'checkUploadingStatus')->name('check_uploading_status');
            Route::get('/check_variant_sku', 'checkVariantSku')->name('checkVariantSku');

            Route::get('/list-products-supplier', 'listProductsSupplier')->name('listProductsSupplier');
            Route::get('/approved-products-supplier/{id}', 'approvedProductsSupplier')->name('approvedProductsSupplier');

            Route::get('/export-product-by-admin', 'exportProductByAdmin')->name('export_product_by_admin');
            Route::get('/related_product/{id}', 'relatedProduct')->name('related_product');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}', 'update')->name('update');
        });

        Route::controller(WarehouseController::class)->prefix('/warehouse')->name('warehouse.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/list', 'list')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });


        Route::controller(BundleController::class)->prefix('/bundles')->name('bundles.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/list', 'list')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });


        Route::controller(EventController::class)->prefix('/event')->name('event.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
        Route::controller(AttributeController::class)->prefix('/attribute')->name('attribute.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
        Route::controller(ProductController::class)->group(function () {
            Route::get('/scan-product-wms', 'scanProductWms')->name('scan.product.wms');

        });

    });
});
