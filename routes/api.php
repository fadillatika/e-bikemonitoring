<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/motorid', [ApiController::class, 'addMotor']);
    Route::post('/tracking', [ApiController::class, 'addTracking']);
    Route::post('/battery', [ApiController::class, 'addBattery']);
    Route::post('/lock', [ApiController::class, 'addLock']);
});

Route::get('/trackings', [ApiController::class, 'getTrackings']);
// Route::get('/search', [SearchController::class, 'search'])->name('search');
// Route::post('/auth/token', [AuthController::class, 'generateToken'])->middleware('throttle:5,1');