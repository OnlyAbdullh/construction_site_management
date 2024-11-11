<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sub_MaterialController;

// Sub-material-related routes
Route::apiResource('sub_materials', Sub_MaterialController::class);
Route::delete('/materials/{internal_reference}/sub_materials/{id}', [Sub_MaterialController::class, 'destroySubMaterialInMaterial']);
