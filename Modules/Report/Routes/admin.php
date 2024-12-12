<?php

use Modules\Report\Http\Controllers\Admin\ReportController;
use Modules\Report\Http\Controllers\Admin\ValidationStatisticsController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'admin', 'auth'], function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('order-sources-report', [ReportController::class, 'orderSourcesReport'])->name('orderSourcesReport');
            Route::get('export-order-sources-report', [ReportController::class, 'exportOrderSourcesReport'])->name('exportOrderSourcesReport');
            Route::get('export-remark-cancellation-rates', [ReportController::class, 'exportRemarkCancellationRates'])->name('exportRemarkCancellationRates');
            
            Route::get('default', [ReportController::class, 'default'])->name('default');
            Route::prefix('product')->name('product.')->group(function () {
                Route::get('all', [ReportController::class, 'allProductReport'])->name('all');
            });
            Route::prefix('performance')->name('performance.')->group(function () {
                Route::get('all', [ReportController::class, 'allperformanceReport'])->name('all');
                Route::get('platform', [ReportController::class, 'platformPerformanceReport'])->name('platform');
                Route::get('product', [ReportController::class, 'productCapastePerformance'])->name('product.capaste');
                Route::get('cancelled', [ReportController::class, 'cancelledPerformance'])->name('cancelled');
                Route::get('payment', [ReportController::class, 'paymentPerformance'])->name('payment');
                Route::get('wms', [ReportController::class, 'wmsPerformance'])->name('wms');
            });
            Route::prefix('validation')->name('validation.')->group(function () {
                Route::get('/index', [ValidationStatisticsController::class, 'index'])->name('index');
                Route::post('/validation-stats', [ValidationStatisticsController::class, 'getStats'])->name('getStats');
                Route::post('/export-validation', [ValidationStatisticsController::class, 'exportValidation'])->name('export.validation');
                Route::post('/validation-orders', [ValidationStatisticsController::class, 'getFilteredOrders'])
                    ->name('getFilteredOrders');
                Route::prefix('performance')->name('performance.')->group(function () {
                    Route::get('all', [ValidationStatisticsController::class, 'allReport'])->name('all');
                });
            });
        });
    });
});
