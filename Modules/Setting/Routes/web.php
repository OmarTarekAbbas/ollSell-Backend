<?php

use Modules\Order\Http\Controllers\AttemptsLogController;
use Modules\Setting\Http\Controllers\SettingController;
use Modules\Setting\Http\Controllers\FailOrderController;

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
Route::group(['middleware' => 'admin', 'auth'], function()
{
    Route::controller(SettingController::class)->prefix('/setting')->name('setting.')->group(function()
    {
        Route::get('/list', 'list')->name('list');
        Route::get('/basic', 'basic')->name('basic');
        Route::post('/basic', 'setBasic')->name('setBasic');
        Route::get('/api_integration', 'editIntegration')->name('api_integration');
        Route::post('api_integration', 'setApiIntegration')->name('setApiIntegration');

        Route::get('/salla_integration', 'editSallaIntegration')->name('salla_integration');
        Route::post('salla_integration', 'setSallaIntegration')->name('salla_integration');
        
        Route::get('/aymakan_integration', 'editAymakanIntegration')->name('aymakan_integration');
        Route::post('aymakan_integration', 'setAymakanIntegration')->name('aymakan_integration');
        

  
        Route::get('/email_setting', 'editEmailSetting')->name('email_setting');
        Route::post('email_setting', 'setEmailSetting')->name('email_setting');
        
          
        Route::get('/ollops_setting', 'editOllopsSetting')->name('ollops_setting');
        Route::post('ollops_setting', 'setOllopsSetting')->name('ollops_setting');
        

        Route::get('/order_setting', 'editOrderSetting')->name('order_setting');
        Route::post('order_setting', 'setOrderSetting')->name('order_setting');

        Route::get('/dropshipper_setting', 'editDropshipperSetting')->name('dropshipper_setting');
        Route::post('dropshipper_setting', 'setDropshipperSetting')->name('dropshipper_setting');

        Route::get('/pay_setting', 'listPaySetting')->name('listPay_setting');
        Route::post('pay_setting', 'paySetting')->name('pay_setting');


        Route::get('/edit', 'edit')->name('edit');
        Route::get('/shipping', 'shipping')->name('shipping');
        Route::post('/shipping/store', 'updateShipping')->name('shipping.store');
        Route::post('update', 'update')->name('update');
    });
    Route::prefix('log')->name('log.')->group(function()
    {
        Route::prefix('request')->name('request.')->group(function()
        {
            Route::controller(RequestLogController::class)->group(function()
            {
                Route::get('/', 'index')->name('index');
            });
        });
        Route::prefix('wms')->name('wms.')->group(function()
        {
            Route::controller(WmsLogController::class)->group(function()
            {
                Route::get('/', 'index')->name('index');
            });
        });
        Route::prefix('easy_order')->name('easy_order.')->group(function()
        {
            Route::controller(EasyOrderController::class)->group(function()
            {
                Route::get('/', 'index')->name('index');
            });
        });
        Route::prefix('validation_log')->name('validation_log.')->group(function()
        {
            Route::get('/', [AttemptsLogController::class, 'index'])->name('index');
            Route::get('/export-attempts-log', [AttemptsLogController::class, 'export'])->name('export');
        });
    });
    Route::prefix('failorder')->name('failorder.')->group(function()
    {
        Route::controller(FailOrderController::class)->group(function()
        {
            Route::get('/', 'index')->name('index');
            Route::post('/changeActive', 'changeActive')->name('changeActive');
        });
    });
});
