<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sub_MaterialController;

// Sub-material-related routes
Route::apiResource('sub_materials', Sub_MaterialController::class);
Route::delete('/sub_materials', [Sub_MaterialController::class, 'destroySubMaterialInMaterial']);
Route::post('/sub-materials/set-quantity', [Sub_MaterialController::class, 'setQuantity']);
