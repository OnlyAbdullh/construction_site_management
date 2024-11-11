<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;


// Material-related routes
Route::get('export-excel', [MaterialController::class, 'export']);
Route::post('import-excel', [MaterialController::class, 'import']);
Route::get('/search/{internal_reference}', [MaterialController::class, 'search']);
Route::apiResource('materials', MaterialController::class);
