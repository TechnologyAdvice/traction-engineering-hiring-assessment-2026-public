<?php

use App\Http\Controllers\MissionController;
use Illuminate\Support\Facades\Route;

Route::get('/missions', [MissionController::class, 'index']);
Route::post('/missions', [MissionController::class, 'store']);
Route::get('/missions/{mission}', [MissionController::class, 'show']);
Route::get('/missions/{mission}/satellites', [MissionController::class, 'satellites']);
