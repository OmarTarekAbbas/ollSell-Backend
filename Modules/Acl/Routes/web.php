<?php

use Illuminate\Support\Facades\Route;
use Modules\Acl\Http\Controllers\DropshipperController;
use Modules\Acl\Http\Controllers\UserController;
use Modules\Acl\Http\Controllers\SupplierController;
use Modules\Acl\Http\Controllers\ForgotPasswordController;

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

Route::controller(AuthController::class)->name('auth.')->group(function () {
    Route::get('/login', 'loginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});
Route::controller(ForgotPasswordController::class)
    ->prefix('/reset/password/generate')->name('reset.password.')->group(function () {
        Route::get('/', 'resetPasswordForm')->name('generate');
        Route::post('/', 'resetPassword')->name('generate.post');
        Route::get('/password/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('/password/reset', 'reset');
        Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('/password/email', 'sendResetLinkEmail')->name('password.email');
    });

Route::group(['middleware' => 'admin', 'auth'], function () {
    Route::prefix('acl')->group(function () {
        Route::controller(RoleController::class)->prefix('/role')->name('role.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/update', 'update')->name('update');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });
        /* User route list */
        Route::resource('user', UserController::class, ['except' => ['show', 'update']])
            ->parameters(['user' => 'id']);
        Route::controller(UserController::class)->prefix('/user')->name('user.')->group(
            function () {
                Route::post('changeStatus', 'changeStatus')->name('changeStatus');
                Route::get('password/{id}', 'changePassword')->name('changePassword');
                Route::post('password/{id}', 'updatePassword')->name('updatePassword');
                Route::post('{id}', 'update')->name('update');
                Route::get('{id}', 'show')->name('show');
                Route::get('/list/users', 'indexForList')->name('indexForList');
            }
        );
        /* Dropshipper route list */
        Route::controller(DropshipperController::class)->prefix('dropshipper')->name('dropshipper.')->group(
            function () {
                Route::get('/orders', 'orders')->name('dropshipper.orders');
                Route::get('/transactions', 'transactions')->name('dropshipper.transactions');
                Route::get('search', 'search')->name('search');
                Route::post('changeStatus', 'changeStatus')->name('changeStatus');
                Route::post('changeBlocked', 'changeBlocked')->name('changeBlocked');
                Route::post('changeStatusPhoneVerification', 'changeStatusPhoneVerification')
                    ->name('changeStatusPhoneVerification');

                Route::post('/update-feature', [DropshipperController::class, 'updateFeature']);
                Route::post('/update-feature-form/{id}', [DropshipperController::class, 'updateFeatureForm']);

                Route::post('/clear-feature', [DropshipperController::class, 'clearFeature']);

                Route::post('changeStatusDropshipperSetting', 'changeStatusDropshipperSetting')
                    ->name('changeStatusDropshipperSetting');
                Route::post('/{id}/update-max-discount', [DropshipperController::class, 'updateMaxDiscount'])
                    ->name('updateMaxDiscount');
            }
        );
        Route::resource('dropshipper', DropshipperController::class, ['except' => ['update']])
            ->parameters(['dropshipper' => 'id']);

        Route::resource('suppliers', SupplierController::class);
        Route::controller(SupplierController::class)->prefix('/supplier')
            ->name('supplier.')->group(
                function () {
                    Route::get('/list', 'list')->name('list');
                }
            );
    });
});
