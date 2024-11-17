<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConcretePourController;

Route::post('concretePours',[ ConcretePourController::class,'store']);
Route::get('concretePours',[ ConcretePourController::class,'index']);
Route::delete('concretePours/{concretePour}',[ ConcretePourController::class,'destroy']);
Route::put('concretePours/{concretePour}',[ ConcretePourController::class,'update']);
