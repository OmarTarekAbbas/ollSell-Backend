<?php

use Illuminate\Http\Request;
use Modules\Setting\Http\Controllers\API\SettingController;
use Modules\Setting\Http\Controllers\EasyOrderController;

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

Route::get('setting', 'API\SettingController@list')->name('setting');
//todo change
Route::any('/shippingFees', [SettingController::class, 'shippingFees']);
Route::prefix('log')->name('log.')->group(function()
{
    Route::prefix('easy_order')->name('easy_order.')->group(function()
    {
        Route::controller(EasyOrderController::class)->group(function()
        {
            Route::get('/download', 'download')->name('download');
        });
    });
});