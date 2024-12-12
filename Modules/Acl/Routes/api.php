<?php

use Modules\Acl\Http\Controllers\Api\AuthController;
use Modules\Acl\Http\Controllers\Api\DropshipperController;
use Modules\Acl\Http\Controllers\Api\DropshipperPaymentController;
use Modules\Acl\Http\Controllers\Api\ResetPasswordController;
use Modules\Acl\Http\Controllers\Api\SendCodeController;

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
        //auth
        Route::prefix('/dropshipper')->name('dropshipper.')->group(function () {
            Route::prefix('/auth')->name('auth.')->group(function () {
                //register
                Route::post('/register', [DropshipperController::class, 'store'])->name('register');
                Route::post('/mega/register', [DropshipperController::class, 'megaRegister'])->name('mega.register');
                //login
                Route::post('/login', [AuthController::class, 'login'])->name('login');
                Route::post('/resetPasswordLink', [ResetPasswordController::class, 'resetPasswordLink']);
                Route::post('/resetPasswordCode', [ResetPasswordController::class, 'resetPasswordCode']);
                Route::post('/verificationCode', [ResetPasswordController::class, 'verificationCode']);

                Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword']);
                Route::post('/resendCode', [AuthController::class, 'resendCode']);
                //step register
                Route::post('/step1-register', [AuthController::class, 'stepOneRegister']);
                Route::post('/step2-register', [SendCodeController::class, 'stepTwoRegister']);
                Route::post('/step3-register', [AuthController::class, 'stepThreeRegister']);
                Route::post('/change-phone-number', [AuthController::class, 'changePhoneNumber']);
            });
            Route::group(['middleware' => 'auth:dropshipper'], function () {
                Route::prefix('/auth')->name('auth.')->group(function () {
                    //email
                    Route::post('/email', [AuthController::class, 'email'])->name('email');
                    Route::post('/profile', [DropshipperController::class, 'profile'])->name('profile');
                    Route::post('/avatar', [DropshipperController::class, 'avatar'])->name('avatar');
                    Route::post('/updatePassword', [DropshipperController::class, 'updatePassword'])
                        ->name('updatePassword');
                    Route::get('/onboarding_questionnaire/answer',
                        [DropshipperController::class, 'getOnboardingQuestionnaireAnswer'])
                        ->name('onboarding_questionnaire.answer');
                    Route::post('/onboarding_questionnaire', [DropshipperController::class, 'onboardingQuestionnaire'])
                        ->name('onboarding_questionnaire');
                    Route::post('/getProfile', [DropshipperController::class, 'profitShow']);
                    //delete Account
                    Route::get('/delete', [DropshipperController::class, 'delete']);
                    Route::prefix('/profit')->group(function () {
                        Route::post('/update', [DropshipperController::class, 'profitUpdate']);
                        Route::get('/show', [DropshipperController::class, 'profitShow']);
                    });
                });
                Route::prefix('/payment')->name('payment.')->group(function () {
                    Route::get('/list', [DropshipperPaymentController::class, 'list']);
                    Route::post('/store', [DropshipperPaymentController::class, 'store']);
                    Route::post('/update', [DropshipperPaymentController::class, 'update']);
                    Route::get('/show/{id}', [DropshipperPaymentController::class, 'show']);
                    Route::delete('/delete/{id}', [DropshipperPaymentController::class, 'delete']);
                    Route::post('/is_main/{id}', [DropshipperPaymentController::class, 'isMain']);
                });
            });
            Route::post('/create-branch', [DropshipperController::class, 'createBranch']);
            Route::post('/update-branch/{id}', [DropshipperController::class, 'updateBranch']);
            Route::get('/list-branch', [DropshipperController::class, 'listBranch']);
        });

    });
});
