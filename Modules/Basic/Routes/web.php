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

Route::group(['middleware' => 'admin', 'auth'], function () {
    Route::prefix('basic')->group(function () {
        Route::get('/', 'BasicController@index');

        Route::controller(CustomTranslationController::class)->prefix('/custom_translation')->name('custom_translation.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::controller(TableHandlerController::class)->prefix('/table_handler')->name('table_handler.')->group(function () {
            Route::get('/set_table_length', 'setTableLength')->name('set_table_length');
        });

        Route::controller(ExportController::class)->name('model.')->group(function () {
            Route::post('/export', 'export')->name('export');
            Route::get('/export', 'download')->name('download.export');
            Route::get('/job/status', 'getJobStatus')->name('getJobStatus');
        });
    });
});
//todo change
Route::middleware(['auth:supplier'])->name('supplier.')->group(function () {
    Route::controller(ExportController::class)->name('model.')->group(function () {
        Route::post('/export', 'export')->name('export');
        Route::get('/supplier/export', 'download')->name('download.export')->withoutMiddleware(['auth:supplier']);
        Route::get('/job/status', 'getJobStatus')->name('getJobStatus');
    });
});

Route::controller(AjaxController::class)->name('ajax.')->group(function () {
    Route::get('/get-cities-baed-on-country-id', 'getCitiesBaedOnCountryId')->name('cities');
    Route::get('/get-attributes-validations', 'getAttributesVariation')->name('attributes');
});
