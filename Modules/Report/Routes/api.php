<?php

use Modules\Report\Http\Controllers\Dropshipper\NewsletterController;
use Modules\Report\Http\Controllers\Dropshipper\ReportController;

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

Route::group(['middleware' => 'api', 'language'], function () {
    Route::name('api.')->group(function () {
        Route::group(['middleware' => 'auth:dropshipper'], function () {
            Route::prefix('/report')->name('report.')->group(function () {
                Route::get('/requests', [ReportController::class, 'reportRequests']);
                Route::get('/financial', [ReportController::class, 'reportFinancial']);
            });
        });
        Route::post('report/newsletter', [NewsletterController::class, 'newsletter']);
    });
});