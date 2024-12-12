<?php

use Illuminate\Support\Facades\Route;
use Modules\CoreData\Http\Controllers\StatusController;
use Modules\CoreData\Http\Controllers\NotificationController;

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

Route::group(['middleware' => 'admin', 'auth'], function () {
    Route::controller(CityController::class)->prefix('/city')
    ->name('city.')->group(
        function()
        {
            Route::get('/list', 'list')->name('list');
        }
    );
    Route::prefix('coredata')->middleware('suspended')->group(function () {
        //Country
        Route::controller(CountryController::class)->prefix('/country')->name('country.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        //City
        Route::controller(CityController::class)->prefix('/city')->name('city.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
  

        //State
        Route::controller(StateController::class)->prefix('/state')->name('state.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

       // dropshipper_segmentation
       Route::controller(DropshipperSegmentationController::class)->prefix('/dropshipper_segmentation')->name('dropshipper_segmentation.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::post('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
        //Language
        Route::controller(LanguageController::class)->prefix('/language')->name('language.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/change-lang/{lang}', 'language')->name('change-lang');
        });


        //TargetMarket
        Route::controller(TargetMarketController::class)->prefix('/target_market')->name('target_market.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // suggested categories
        Route::controller(SuggestedCategoryController::class)->prefix('/suggestedCategories')->name('suggestedCategories.')->group(function () {
            Route::get('/list', 'listCategoriesSupplier')->name('listCategoriesSupplier');
            Route::get('/{id}', 'showSuggestedCategory')->name('show');
            Route::post('/reject-products-supplier', 'rejectCategoriesSupplier')->name('rejectCategoriesSupplier');
            Route::post('/{id}', 'storeSuggested')->name('storeSuggested');
        });

        //Category
        Route::controller(CategoryController::class)->prefix('/category')->name('category.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/change/Commission', 'changeCommission')->name('changeCommission');
        });
        //OnboardingCategory
        Route::controller(OnboardingCategoryController::class)->prefix('/onboarding_category')->name('onboarding_category.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}', 'show')->name('show');
        });
        //Status
        Route::controller(StatusController::class)->prefix('/status')->name('status.')->group(function () {
            Route::post('/changeStatus', 'changeStatus')->name('changeStatus');
            Route::get('/index', 'index')->name('index');
            Route::get('/create',  'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('/store', 'store')->name('store');
        });

        //todo change
        Route::controller(NotificationController::class)->name('notification.')->group(function () {
            Route::post('/mark-notification-as-seen', 'markNotificationAsSeen')->name('markNotificationAsSeen');
        });

        Route::get('mark_notifications-as-red', [NotificationController::class, 'markAllAsSeen'])->name('mark_notifications_as_red');
    });
});



//todo change
Route::middleware(['auth:supplier'])->name('supplier.')->group(function () {
    Route::controller(NotificationController::class)->name('notification.')->group(function () {
        Route::post('/supplier/mark-notification-as-seen', 'markNotificationAsSeen')->name('markNotificationAsSeen');
    });
});
