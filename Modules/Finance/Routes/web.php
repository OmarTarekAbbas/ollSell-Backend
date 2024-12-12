<?php

use Modules\Finance\Http\Controllers\DepositRequestController;
use Modules\Finance\Http\Controllers\WithdrawalRequestController;

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

Route::prefix('/withdrawalRequest')->name('withdrawalRequest.')->group(function () {
    // list records
    Route::get('/upload/{id}', [WithdrawalRequestController::class, 'showUploadForm'])->name('upload.form');
    Route::post('/upload', [WithdrawalRequestController::class, 'uploadImage'])->name('upload.image');
    Route::get('list', [WithdrawalRequestController::class, 'index'])->name('list');
    Route::get('{id}', [WithdrawalRequestController::class, 'show'])->name('show');
    // approved record
    Route::get('approved/{id}', [WithdrawalRequestController::class, 'approved'])->name('approved');
    // refused record
    Route::get('refused/{id}', [WithdrawalRequestController::class, 'refused'])->name('refused');
    // refused record
    Route::get('inProgress/{id}', [WithdrawalRequestController::class, 'inProgress'])->name('inProgress');

    Route::post('chats/{withdrawalRequestId}', [WithdrawalRequestController::class, 'storeChat'])->name('chats.store');
});

Route::prefix('/depositRequest')->name('depositRequest.')->group(function () {
    // list records
    Route::get('list', [DepositRequestController::class, 'index'])->name('list');
    Route::get('/{id}', [DepositRequestController::class, 'show'])->name('show');
    // approved record
    Route::get('approved/{id}', [DepositRequestController::class, 'approved'])->name('approved');
    // refused record
    Route::get('refused/{id}', [DepositRequestController::class, 'refused'])->name('refused');
});
