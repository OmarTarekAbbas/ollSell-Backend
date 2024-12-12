<?php

use Modules\Subscription\Http\Controllers\Dashboard\FeatureController;

Route::prefix('subscription')->group(function() {

    Route::prefix('/feature')->name('feature.')->group(function () {
        Route::get('/{id}/edit', [FeatureController::class, 'edit']);
        Route::patch('/{id}', [FeatureController::class, 'update']);
        Route::resource('/', FeatureController::class);
    });
});

