<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MotoruserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchuserController;


// Menggabungkan home dan /home ke satu rute
Route::redirect('/home', '/');

Route::get('/', function () {
    return view('home', [
        "title" => "Home"
    ]);
})->name('home');

// Route::get('/account', function () {
//     return view('account', [
//         "title" => "Account"
//     ]);
// })->name('account')->middleware('auth.admin');

Route::get('/data', function () {
    return view('data', [
        "title" => "Data"
    ]);
})->name('data')->middleware('auth.admin');

Route::get('/about', function () {
    return view('about', [
        "title" => "About",
    ]);
})->name('about');

Route::get('/monitor', [MotorController::class, 'showView'])->name('monitor');

Route::get('/monitoruser', [MotoruserController::class, 'index'])->name('monitoruser')->middleware('auth.admin');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/download-data', [DownloadController::class, 'downloadData'])->name('downloadData')->middleware('auth.admin');

Route::get('/user',  [SearchuserController::class, 'search'])->name('user.search')->middleware('auth.admin');

Route::get('/account',  [AccountController::class, 'account'])->name('account')->middleware('auth.admin');