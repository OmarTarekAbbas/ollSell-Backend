<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\Api\DepositRequestController;
use Modules\Finance\Http\Controllers\Api\FinanceController;
use Modules\Finance\Http\Controllers\Api\WithdrawalRequestController;

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

Route::group(['middleware' => 'api', 'language', 'auth:dropshipper'], function () {
    Route::name('api.')->group(function () {
        Route::prefix('/wallet')->name('wallet.')->group(function () {
            // create WithdrawalRequest
            Route::post('withdrawalRequest', [WithdrawalRequestController::class, 'store']);
            Route::get('list-withdrawalRequest', [WithdrawalRequestController::class, 'list']);
             //Payment profit
             Route::get('/payment/profit', [FinanceController::class, 'PaymentProfit']);
            Route::get('/wallet/export', [FinanceController::class, 'walletExport']);
             Route::post('depositRequest', [DepositRequestController::class, 'store']);
             Route::get('list-depositRequest', [DepositRequestController::class, 'list']);
             Route::get('list-wallet', [FinanceController::class, 'listWallet']);
             Route::post('withdrawal-requests/{withdrawalRequest}/chats', [WithdrawalRequestController::class, 'storeChat']);
             Route::get('withdrawal-requests/{withdrawalRequest}/chats', [WithdrawalRequestController::class, 'listChat']);


             Route::post('earningsWithdrawal', [WithdrawalRequestController::class, 'earningsWithdrawal']);
        });
    });
});