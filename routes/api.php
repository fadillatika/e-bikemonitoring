<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\GetLastDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistorymapController;
use App\Http\Controllers\PredictionController;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/motorid', [ApiController::class, 'addMotor']);
    // Route::post('/update-email', [AccountController::class, 'updateEmail'])->name('admin.update-email');
    // Route::post('/tracking', [ApiController::class, 'addTracking']);
    // Route::post('/battery', [ApiController::class, 'addBattery']);
    // Route::post('/lock', [ApiController::class, 'addLock']);
});

Route::get('/countTrackings', [MotorController::class, 'getTrackings']);
Route::post('/create-token', [UserController::class, 'createUser']);

// Route::post('/lock', [ApiController::class, 'addLock']);

// Route::post('/battery', [ApiController::class, 'addBattery']);

// Route::get('/fetchtsgps', [ApiController::class, 'fetchTSGPS']);
Route::get('/fetchtsbattery', [ApiController::class, 'fetchTSBattery']);
Route::post('/predict', [ApiController::class, 'predict']);
// Route::get('/fetchtslock', [ApiController::class, 'fetchTSLock']);

Route::get('/dataterakhir', [GetLastDataController::class, 'index']);

Route::get('/roadhistory', [GetLastDataController::class, 'gethistorydata']);


// Route::get('/getAllTrackingData', [HistorymapController::class, 'getAllTrackingData']);

Route::get('/get-prediction', [PredictionController::class, 'getPrediction']);

Route::post('/auth/token', [AuthController::class, 'generateToken'])->middleware('throttle:5,1');

Route::get('/checkdata', [GetLastDataController::class, 'checkData']);
