<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\MotorController;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/motorid', [ApiController::class, 'addMotor']);
    Route::post('/tracking', [ApiController::class, 'addTracking']);
    Route::post('/battery', [ApiController::class, 'addBattery']);
    Route::post('/lock', [ApiController::class, 'addLock']);
});

Route::get('/countTrackings', [MotorController::class, 'getTrackings']);
Route::get('/motors', [MotorController::class, 'index']);
// Route::get('/search', [SearchController::class, 'search'])->name('search');
// Route::post('/auth/token', [AuthController::class, 'generateToken'])->middleware('throttle:5,1');