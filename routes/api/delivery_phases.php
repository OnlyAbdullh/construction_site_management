
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryPhasesController;

// Delivery phases-related routes within a site
Route::prefix('sites/{site}')->controller(DeliveryPhasesController::class)->group(function () {
Route::get('/delivery-phases', 'index');
Route::post('/delivery-phases', 'store');
Route::get('/delivery-phases/{deliveryPhase}', 'show');
Route::put('/delivery-phases/{deliveryPhase}', 'update');
Route::delete('/delivery-phases/{deliveryPhase}', 'destroy');
});
