<?php

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

Route::get('export-excel', [MaterialController::class, 'export']);
Route::post('import-excel', [MaterialController::class, 'import']);
Route::get('/search/{internal_reference}', [MaterialController::class, 'search']);
Route::apiResource('materials', MaterialController::class);
Route::apiResource('sites', SiteController::class);
Route::delete('/sites/{siteId}/materials/{materialId}', [SiteController::class, 'deleteMaterial']);
Route::get('/sites/{siteId}/materials/{internal_reference}', [SiteController::class, 'searchMaterialInSite']);
Route::apiResource('sub_materials', Sub_MaterialController::class);
