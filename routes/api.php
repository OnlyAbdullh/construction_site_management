<?php

use App\Http\Controllers\MaterialController;
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
Route::get('all', [MaterialController::class, 'index']);
Route::get('/search/{internal_reference}', [MaterialController::class, 'search']);
Route::get('/materiels/{materiel}', [MaterialController::class, 'show']);
Route::put('/materials/{material}', [MaterialController::class, 'update']);
