<?php

use Illuminate\Support\Facades\Route;
use Modules\Basic\Http\Controllers\AjaxController;
use Modules\Order\Http\Controllers\CancelledOrdersController;
use Modules\Order\Http\Controllers\NoteController;
use Modules\Order\Http\Controllers\OrderController;
use Modules\Order\Http\Controllers\RefundController;
use Modules\Order\Http\Controllers\FollowUpController;
use Modules\Order\Http\Controllers\OrderListController;
use Modules\Order\Http\Controllers\SubStatusController;
use Modules\Order\Http\Controllers\OrderScheduleController;
use Modules\Order\Http\Controllers\SheetController;
use Modules\Order\Http\Controllers\FakeController;

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

Route::prefix('/order')->name('order.')->group(function () {
    // google sheet
    Route::get('/sheet/test', [SheetController::class, 'test']);

    // list records
    Route::get('/deliveredAymakan', [OrderController::class, 'webhooksShipping'])->name('deliveredAymakan');
    Route::get('list', [OrderController::class, 'index'])->name('listOrders');
    Route::get('create', [OrderController::class, 'create'])->name('create');
    Route::patch('update/{order}', [OrderController::class, 'update'])->name('update');
    Route::post('store', [OrderController::class, 'store'])->name('store');
    Route::get('logistics', [OrderController::class, 'logistics'])->name('Logistics');
    Route::post('changeOrderStatus', [OrderController::class, 'changeOrderStatus'])->name('changeOrderStatus');


    //Enhanced order listing
    Route::get('listing', [OrderListController::class, 'index'])->name('listing.index');
    Route::post('listing', [OrderListController::class, 'orders'])->name('listing.orders');
    Route::get('listing/getStatuses', [OrderListController::class, 'getStatuses'])->name('getStatuses');
    Route::get('listing/orderLogs/{id}', [OrderListController::class, 'orderLogs'])->name('listing.orderLogs');
    Route::post('/update-address/{id}', [OrderListController::class, 'updateOrderAddress'])->name('listing.updateOrderAddress');
    Route::post('/update-order-status', [OrderListController::class, 'updateOrderStatus'])->name('listing.updateOrderStatus');
    Route::post('/update-substatus', [OrderListController::class, 'updateOrderSubStatus'])->name('listing.updateOrderSubStatus');
    Route::post('/update-remark', [OrderListController::class, 'updateOrderRemark'])->name('listing.updateOrderRemark');
    Route::post('/list/getSubStatuses', [OrderListController::class, 'getSubStatuses'])->name('listing.getSubStatuses');
    Route::post('/list/getDropshippers', [OrderListController::class, 'getDropshippers'])->name('listing.getDropshippers');
    Route::post('/list/getProducts', [OrderListController::class, 'getProducts'])->name('listing.getProducts');
    Route::post('/list/getRemarks', [OrderListController::class, 'getRemarks'])->name('listing.getRemarks');
    Route::post('/list/getOperators', [OrderListController::class, 'getOperators'])->name('listing.getOperators');
    Route::post('/list/bulk-update-status', [OrderListController::class, 'bulkUpdateStatus'])->name('listing.bulkUpdateStatus');
    Route::post('/list/validate/{id}', [OrderListController::class, 'validateOrder'])->name('listing.validateOrder');
    Route::post('/list/updateAttempts/{id}', [OrderListController::class, 'updateAttempts'])->name('listing.updateAttempts');
    Route::get('/list/getCitiesBaedOnCountryId', [OrderListController::class, 'getCitiesBaedOnCountryId'])->name('listing.getCitiesBaedOnCountryId');
    Route::post('/list/export', [OrderListController::class, 'export'])->name('listing.export');
    Route::post('/list/export-advanced', [OrderListController::class, 'exportAdvanced'])->name('listing.exportAdvanced');
    Route::post('/list/import', [OrderListController::class, 'import'])->name('listing.import');
    Route::post('orders/assign', [OrderListController::class, 'assign']);
    Route::post('update/{id}/quantity', [OrderListController::class, 'updateQuantity']);
    Route::post('update/{id}/discount', [OrderListController::class, 'updateDiscount']);
    Route::post('validation/startValidationFlow', [OrderListController::class, 'startValidationFlow']);
    Route::get('products/search', [OrderListController::class, 'searchProducts']);
    Route::post('products/{id}/add-product', [OrderListController::class, 'addItem']);
    Route::get('messageTemplates', [OrderListController::class, 'messageTemplates']);
    Route::post('sendMessage', [OrderListController::class, 'sendMessage']);

    // cancelled orders
    Route::get('cancelled', [CancelledOrdersController::class, 'index'])->name('cancelled.index');
    Route::get('cancelled/list', [CancelledOrdersController::class, 'orders'])->name('cancelled.orders');

    // refunds
    Route::resource('refund', RefundController::class);
    // Approve/refuse refund request
    Route::get('refund/action/{id}', [RefundController::class, 'action'])->name('refund.action');
    Route::get('refund/startShipping/{id}', [RefundController::class, 'startShipping'])->name('refund.startShipping');
    Route::get('refund/refundBalance/{id}', [RefundController::class, 'refundBalance'])->name('refund.refundBalance');

    // follow-up
    Route::get('followUp', [FollowUpController::class, 'index'])->name('followUp.index');
    Route::get('followUp/{order}', [FollowUpController::class, 'show'])->name('followUp.show');
    Route::get('followUp/{order}/getFollowUps', [FollowUpController::class, 'getFollowUps'])->name('followUp.getFollowUps');
    Route::post('/followUp/{order}', [FollowUpController::class, 'save'])->name('followUp.save');
    Route::post('/followUp/{order}/schedule', [OrderScheduleController::class, 'save'])->name('followUp.schedule.save');
    Route::post('/followUp/{order}/markAsSatisfied', [OrderScheduleController::class, 'markAsSatisfied'])->name('followUp.schedule.markAsSatisfied');

    // sub status
    Route::controller(SubStatusController::class)->prefix('/subStatus')->name('subStatus.')->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::get('/create',  'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('{id}', 'destroy')->name('destroy');
        Route::post('/store', 'store')->name('store');
        Route::post('/getNextStatusOptions', 'getNextStatusOptions')->name('getNextStatusOptions');
        Route::post('/getNextSubStatusOptions', 'getNextSubStatusOptions')->name('getNextSubStatusOptions');
        Route::post('/getNextRemarkOptions', 'getNextRemarkOptions')->name('getNextRemarkOptions');

    });

    // show id
    Route::get('/{id}', [OrderController::class, 'showOrder'])->name('show');
    Route::get('/logs/{id}', [OrderController::class, 'showLogs'])->name('showLogs');

    // approved record
    Route::get('approved/{id}', [OrderController::class, 'approved'])->name('approved');
    Route::get('edit/{order}', [OrderController::class, 'editOrder'])->name('editOrder');
    Route::post('startShipping', [OrderController::class, 'startShipping'])->name('startShipping');
    // refused record
    Route::get('refused/{id}', [OrderController::class, 'refused'])->name('refused');

    // list messages
    Route::get('/list/messages', [OrderController::class, 'listMessages']);
    //send messages
    Route::post('/send/message/{id}', [OrderController::class, 'sendMessages'])->name('sendMessage');

    // webhooks/shipping

    // ajax calls
    Route::post('/checkOrder/{id}', [AjaxController::class, 'checkOrder'])->name('checkOrder');

    // notes
    Route::post('/notes/{id}/storeNote', [NoteController::class, 'storeNote'])->name('note.storeNote');
    Route::put('/notes/{id}', [NoteController::class, 'update'])->name('note.update');
    Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('note.destroy');
});
Route::prefix('/invoice')->name('invoice.')->group(function () {
    Route::get('listing', [OrderController::class, 'invoiceList'])->name('listing.index');
});
Route::prefix('/fake')->name('fake.')->group(function () {
    Route::get('/list', [FakeController::class, 'index'])->name('index');
    Route::get('/scan', [FakeController::class, 'scan'])->name('scan');
});



