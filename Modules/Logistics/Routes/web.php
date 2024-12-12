<?php

use Illuminate\Support\Facades\Route;
use Modules\Logistics\Http\Controllers\ShippingCompanyController;
use Modules\Logistics\Http\Controllers\ReportController;
use Modules\Logistics\Http\Controllers\ShippingCompanyCityTimeController;
use Modules\Logistics\Http\Controllers\ShippingCompanyVacationController;
use Modules\Logistics\Http\Controllers\ReportAymakanController;
use Modules\Logistics\Http\Controllers\AymakanInsightsController;

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
    Route::prefix('logistics')->middleware('suspended')->group(function () {
    
        Route::get('report', [ReportController::class, 'index'])->name('report');
        Route::get('reportAymakan', [ReportAymakanController::class, 'index'])->name('reportAymakan');

        Route::get('aymakanInsights', action: [AymakanInsightsController::class, 'index'])->name('aymakanInsights');
        Route::get('orderAymakanInsights', action: [AymakanInsightsController::class, 'orderAymakanInsights'])->name('orderAymakanInsights');
        Route::post('/exportInsightsOrdersReporting', [AymakanInsightsController::class, 'exportInsightsOrdersReporting'])->name('exportInsightsOrdersReporting');

        

        Route::get('/orderAymakan', [ReportAymakanController::class, 'orderAymakan'])->name('orderAymakan');
        
        Route::post('/exportCustamOrdersReporting', [ReportAymakanController::class, 'exportCustamOrdersReporting'])->name('exportCustamOrdersReporting');

      
        Route::post('/exportReporting', [ReportController::class, 'exportReporting'])->name('exportReporting');
        Route::post('/exportCustamReporting', [ReportController::class, 'exportCustamReporting'])->name('exportCustamReporting');

        
        Route::get('/orderCities', [ReportController::class, 'orderCities'])->name('orderCities');
        Route::get('/orderTimes', [ReportController::class, 'orderTimes'])->name('orderTimes');


        Route::get('/orderAll', [ReportController::class, 'orderAll'])->name('orderAll');

        
        Route::controller(ShippingCompanyController::class)->prefix('/shipping_companies')->name('shipping_companies.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::controller(ShippingCompanyCityTimeController::class)->prefix('/shipping_company_city_time')->name('shipping_company_city_time.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::controller(ShippingCompanyVacationController::class)->prefix('/shipping_company_vacation')->name('shipping_company_vacation.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        

  
    });
});

