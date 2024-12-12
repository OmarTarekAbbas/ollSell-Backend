<?php

use Illuminate\Http\Request;
use Modules\Webhooks\Http\Controllers\WebhooksController;

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
        Route::name('webhook.')->group(function () {
            Route::get('webhook/events/list', [WebhooksController::class, 'eventsList']);

            Route::get('/webhook', [WebhooksController::class, 'index']); 
            Route::post('/webhook', [WebhooksController::class, 'store']); 
            Route::delete('/webhook/{event}', [WebhooksController::class, 'destroy']); 
        });
    });
});
