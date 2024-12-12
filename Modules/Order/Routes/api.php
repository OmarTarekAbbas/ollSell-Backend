<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\CartController;
use Modules\Order\Http\Controllers\Api\OrderController;
use Modules\Order\Http\Controllers\OllopsWebhookController;
use Modules\Order\Http\Controllers\Api\OrderStatusController;
use Modules\Order\Http\Controllers\Api\PendingToOrderController;
use Modules\Order\Http\Controllers\Api\PendingOrderImportController;

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
Route::group(['middleware' => 'api', 'language'], function()
{
    Route::name('api.')->group(function()
    {
        Route::prefix('/order')->name('order.')->group(function()
        {
            //import from Order
            Route::post('/export', [OrderController::class, 'export']);
            Route::get('/history', [OrderStatusController::class, 'getOrderStatuses']);
            Route::post('/list/updateAttempts/{id}', [OrderController::class, 'updateAttempts']);
        });
    });
});
Route::group(['middleware' => 'api', 'language', 'auth:dropshipper'], function()
{
    Route::name('api.')->group(function()
    {
        Route::prefix('/order')->name('order.')->group(function()
        {
            Route::post('/pendingTransfersOrder', [PendingToOrderController::class, 'pendingTransfersOrder']);
            Route::get('/pendingTransfersOrderAll', [PendingToOrderController::class, 'pendingTransfersOrderAll']);
            Route::post('/pendingDeleteArray', [PendingToOrderController::class, 'pendingDeleteArray']);
            Route::get('/pendingDeleteAll', [PendingToOrderController::class, 'pendingDeleteAll']);
            Route::get('/ScanAll', [PendingOrderImportController::class, 'scanOrder']);
            Route::post('/import-pending-orders', [PendingOrderImportController::class, 'importPendingOrders']);
            Route::get('/list-pending-orders', [PendingOrderImportController::class, 'list']);
            Route::post('/export-pending-orders', [PendingOrderImportController::class, 'export']);
            Route::delete('/pending-orders/delete/{id}', [PendingOrderImportController::class, 'destroy']);
            Route::post('/pending-orders/updatePendingOrder/{id}',
                [PendingOrderImportController::class, 'updatePendingOrder']);
            // list records
            Route::any('list', [OrderController::class, 'list']);
            Route::any('report-list', [OrderController::class, 'reportList']);
            // create Order
            Route::post('store', [OrderController::class, 'store']);
            Route::post('store-order-now', [OrderController::class, 'storeOrderNow']);
            // edit Order
            Route::post('update/{id}', [OrderController::class, 'update']);
            // one record
            Route::get('show/{id}', [OrderController::class, 'show']);
            // list payment methods
            Route::get('/payment-methods', [OrderController::class, 'paymentMethods']);
            //export from Order
            Route::get('/export', [OrderController::class, 'export']);
            //download Sample from Order
            Route::get('/downloadSample', [OrderController::class, 'getDownload'])->name('getDownload');
            //pay wallet Order
            Route::post('/pay-wallet', [OrderController::class, 'payWallet']);
            //track Order
            Route::get('/track/{id}', [OrderController::class, 'track']);
            //webhooks list
            Route::get('/webhooks/list', [OrderController::class, 'webhooksList']);
            Route::post('/create/cityByAymakan', [OrderController::class, 'cityByAymakan']);
            Route::post('/update-aymakan-status', [OrderController::class, 'updateAymakanStatus']);
            //create/webhook
            Route::get('/update/webhook', [OrderController::class, 'updateWebhooks']);
            // webhooks/shipping
            // Route::post('/webhooks/shipping', [OrderController::class, 'webhooksShipping']);
            //cancel Order
            Route::post('/cancel/{id}', [OrderController::class, 'cancel']);
            //refund orderItem
            Route::post('/refund/orderItem', [OrderController::class, 'refundOrderItem']);
            //refund totalOrder
            Route::post('/refund/total-order', [OrderController::class, 'totalOrder']);
            //list refund Order
            Route::get('/list/refund', [OrderController::class, 'listRefundOrder']);
            //action refund Requested
            Route::post('/action/refundRequested/{id}', [OrderController::class, 'actionRefundRequested']);
            //action refund Replacement
            Route::post('/action/refundReplacement/{id}', [OrderController::class, 'actionRefundReplacement']);
            // list messages
            Route::get('/list/messages', [OrderController::class, 'listMessages']);
            //send messages
            Route::post('/send/message/{id}', [OrderController::class, 'sendMessages']);
            //send sms
            Route::get('/create/smsCode', [OrderController::class, 'smsCode']);
        });
        /*
        Route::apiresource('cart', CartController::class, ['except' => ['show', 'update']])
            ->parameters(['cart' => 'id']);
        */
        // Define individual routes for CartController methods
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        //Route::get('/cart/list', [CartController::class, 'list'])->name('cart.list');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        //Route::post('/cart', [CartController::class, 'list'])->name('cart.list');
        
        Route::get('/cart/{id}', [CartController::class, 'show'])->name('cart.show'); // Removed as per your request
        Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update'); // Removed as per your request
        Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::prefix('/cart')->name('cart.')->group(function()
        {
            Route::post('/delete/product/{id}', [CartController::class, 'deleteProduct']);
            Route::post('/update/{id}', [CartController::class, 'update']);
        });
    });
});
// ollops webhook
Route::post('order/ollops/webhook/handle-update', [OllopsWebhookController::class, 'handleOrderUpdate']);
