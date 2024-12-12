<?php

use Illuminate\Support\Facades\Route;
use Modules\CoreData\Http\Controllers\Api\CategoryController;
use Modules\CoreData\Http\Controllers\Api\SourcePlatformController;
use Modules\CoreData\Http\Controllers\Api\CityController;
use Modules\CoreData\Http\Controllers\Api\CountryController;
use Modules\CoreData\Http\Controllers\Api\LanguageController;
use Modules\CoreData\Http\Controllers\Api\NotificationController;
use Modules\CoreData\Http\Controllers\Api\OnboardingCategoryController;
use Modules\CoreData\Http\Controllers\Api\StateController;
use Modules\CoreData\Http\Controllers\Api\StatusController;
use Modules\CoreData\Http\Controllers\Api\TargetMarketController;
use Modules\MasterCatalog\Http\Controllers\Api\ProductController;

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
        Route::prefix('/coredata')->name('coredata.')->group(function () {
            //language
            Route::prefix('/language')->group(function () {
                Route::any('/list', [LanguageController::class, 'list'])->name('list');
            });

            //country
            Route::prefix('/country')->name('country.')->group(function () {
                Route::any('/list', [CountryController::class, 'list'])->name('list');
                Route::any('/listCode', [CountryController::class, 'listCode']);
            });

            //TargetMarket
            Route::prefix('/target_market')->name('target_market.')->group(function () {
                Route::any('/list', [TargetMarketController::class, 'list'])->name('list');
            });

            //category
            Route::prefix('/category')->name('category.')->group(function () {
                Route::any('/list', [CategoryController::class, 'list'])->name('list');
                Route::any('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::any('/single/{id}', [CategoryController::class, 'single'])->name('single');
            });
            //OnboardingCategory
            Route::prefix('/onboarding_category')->name('onboarding_category.')->group(function () {
                Route::any('/list', [OnboardingCategoryController::class, 'list'])->name('list');
            });
            //city
            Route::prefix('/city')->name('city.')->group(function () {
                Route::any('/list', [CityController::class, 'list'])->name('list');

                Route::get('/all', [CityController::class, 'listShippingCities']);
            });

            //state
            Route::prefix('/state')->name('state.')->group(function () {
                Route::any('/list', [StateController::class, 'list'])->name('list');
            });

            //status
            Route::prefix('/status')->name('status.')->group(function () {
                Route::any('list', [StatusController::class, 'list'])->name('list');
            });
            //todo change
            //status
            Route::prefix('/notifications')->name('notifications.')->group(function () {
                Route::any('list', [NotificationController::class, 'list'])->name('list');
                Route::any('mark-all-as-seen', [NotificationController::class, 'markAllAsSeen'])->name('markAllAsSeen');
                Route::post('delete/{id}', [NotificationController::class, 'deleteNotification'])->name('deleteNotification');
            });
            //SourcePlatform
            Route::prefix('/source_platform')->name('source_platform.')->group(function () {
                Route::post('/list', [SourcePlatformController::class, 'list'])->name('list');
            });
        });

    });
});
