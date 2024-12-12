<?php
use Modules\Report\Http\Controllers\Supplier\ReportController;
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
Route::middleware(['auth:supplier'])->prefix('supplier')->name('supplier.')->group(function()
{
    Route::prefix('report')->name('report.')->group(function()
    {
        Route::controller(ReportController::class)->group(function()
        {
            Route::get('/', 'default')->name('default');
            Route::prefix('product')->name('product.')->group(function()
            {
                Route::get('all', [ReportController::class, 'allProductReport'])->name('all');
                Route::get('get', [ReportController::class, 'getAllProduct'])->name('get');
            });
        });
    });
});