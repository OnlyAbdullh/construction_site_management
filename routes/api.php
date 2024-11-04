<?php

use App\Http\Controllers\delivery_phasesController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Sub_MaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Material-related routes
Route::get('export-excel', [MaterialController::class, 'export']);
Route::post('import-excel', [MaterialController::class, 'import']);
Route::get('/search/{internal_reference}', [MaterialController::class, 'search']);
Route::apiResource('materials', MaterialController::class);

// Sub-material-related routes
Route::apiResource('sub_materials', Sub_MaterialController::class);
Route::delete('/materials/{internal_reference}/sub_materials/{id}', [Sub_MaterialController::class, 'destroySubMaterialInMaterial']);

// Site-related routes
Route::prefix('sites')->controller(SiteController::class)->group(function () {
    Route::apiResource('/', SiteController::class)->parameters(['' => 'site']);
    Route::post('{siteId}/materials', 'addMaterialToSite');
    Route::delete('{siteId}/materials/{internal_reference}', 'deleteMaterial');
    Route::get('{siteId}/materials/{internal_reference}', 'searchMaterialInSite');
    Route::match(['get', 'post'], 'search', 'search');
    Route::get('{site}/calculate-capital', 'calculate_capital');
    Route::get('{site}/calculate-price', 'calculate_price');
});
// Delivery phases-related routes within a site
Route::prefix('sites/{site}')->controller(DeliveryPhasesController::class)->group(function () {
    Route::get('/delivery-phases', 'index');
    Route::post('/delivery-phases', 'store');
    Route::get('/delivery-phases/{deliveryPhase}', 'show');
    Route::put('/delivery-phases/{deliveryPhase}', 'update');
    Route::delete('/delivery-phases/{deliveryPhase}', 'destroy');
});
