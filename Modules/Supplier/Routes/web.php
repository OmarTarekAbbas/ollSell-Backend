<?php
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

use Illuminate\Support\Facades\Route;
use Modules\Basic\Http\Controllers\ExportController;
use Modules\Supplier\Http\Controllers\AuthController;
use Modules\Supplier\Http\Controllers\ProductController;
use Modules\Supplier\Http\Controllers\DashboardController;
use Modules\Supplier\Http\Controllers\OrderController;
use Modules\Supplier\Http\Controllers\WarehouseController;
//todo change
Route::prefix('supplier')->name('supplier.')->group(function()
{
    Route::controller(AuthController::class)->name('auth.')->group(function()
    {
        Route::get('/login', 'loginForm')->name('login.form')->middleware('guest');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout');
    });
    Route::middleware(['auth:supplier'])->group(function()
    {
        Route::controller(WarehouseController::class)->prefix('warehouse')->name('warehouse.')
            ->group(function()
            {
                Route::get('/list', 'list')->name('list');
            });
        Route::resource('warehouse', WarehouseController::class);

        Route::get('/get_category', [ProductController::class, 'getCategoryBySupplier']);
        Route::controller(ProductController::class)->prefix('/product')->name('product.')
            ->group(function()
        {
            Route::get('/importfile', 'importFile')->name('importfile');
            Route::get('/importbasicfile', 'importbasicfile')->name('importbasicfile');
            Route::post('/import', 'import')->name('import');
            Route::post('/importBasic', 'importBasic')->name('importBasic');
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/downloadSample/{type}', 'getDownload')->name('getDownload');
            Route::get('/files/download/', 'download')->name('files.download');
            Route::get('/delete_iamge', 'deleteImage')->name('delete_image');
            Route::get('/check_uploading_status', 'checkUploadingStatus')->name('check_uploading_status');
            Route::get('/export-product-by-suppler', 'exportProductBySuppler')->name('export_product_by_suppler');
        });
        Route::prefix('/order')->name('order.')->group(function()
        {
            // list records
            Route::get('updateCheckBoxByReady', [OrderController::class, 'updateCheckBoxByReady'])
                ->name('update_checkBox_by_ready');
            Route::get('logistics', [OrderController::class, 'logistics'])->name('Logistics');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            // Route::get('update_order_item/{id}', [OrderController::class, 'update'])->name('update_order_item');
            Route::post('update_order_item/{id}', [OrderController::class, 'update'])->name('update_order_item');
            Route::get('export/supplier', [OrderController::class, 'export'])->name('export_supplier');
        });
        Route::get('/createCategoryBySupplier', [ProductController::class, 'createCategoryBySupplier'])
            ->name('createCategoryBySupplier');
        Route::post('/storeCategoryBySupplier', [ProductController::class, 'storeCategoryBySupplier'])
            ->name('storeCategoryBySupplier');
    });
});
