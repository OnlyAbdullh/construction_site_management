<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

// Site-related routes
Route::prefix('sites')->controller(SiteController::class)->group(function () {
    Route::apiResource('/', SiteController::class)->parameters(['' => 'site']);
    Route::post('/{site}/materials', 'addMaterialsToSite');
    Route::delete('{site}/materials', 'deleteMaterials');
    Route::get('{siteId}/materials/{internal_reference}', 'searchMaterialInSite');
    Route::match(['get', 'post'], 'search', 'search');
    Route::get('{site}/calculate-capital', 'calculate_capital');
    Route::get('{site}/calculate-price', 'calculate_price');
});
