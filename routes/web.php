<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MotoruserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchuserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgetPassController;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

// Menggabungkan home dan /home ke satu rute
Route::redirect('/home', '/');

Route::get('/', function () {
    return view('home', [
        "title" => "Home"
    ]);
})->name('home');

Route::get('/data', function () {
    return view('data', [
        "title" => "Data"
    ]);
})->name('data')->middleware('auth.admin');

Route::get('/informatiion', function () {
    return view('informatiion', [
        "title" => "Information",
    ]);
})->name('informatiion')->middleware('auth.admin');

// Route::get('/list', function () {
//     return view('list', [
//         "title" => "List"
//     ]);
// })->name('data');

Route::get('/about', function () {
    return view('about', [
        "title" => "About",
    ]);
})->name('about');

Route::get('/information', function () {
    return view('information', [
        "title" => "Information",
    ]);
})->name('information');

// Route::get('/monitor', [MotorController::class, 'index'])->name('monitor');

// Route::get('/stream', [MotorController::class, 'stream']);

Route::get('/monitoruser', [MotoruserController::class, 'index'])->name('monitoruser')->middleware('auth.admin');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/downloadtracking', [DownloadController::class, 'downloadTrackingData'])->name('downloadTrackingData')->middleware('auth.admin');

Route::get('/downloadbatteries', [DownloadController::class, 'downloadBatteryData'])->name('downloadBatteryData')->middleware('auth.admin');

Route::get('/user',  [SearchuserController::class, 'search'])->name('user.search')->middleware('auth.admin');

Route::get('/account',  [AccountController::class, 'account'])->name('account')->middleware('auth.admin');

Route::post('/admin/update-email', [AdminController::class, 'updateEmail'])->name('admin.updateEmail');

// Route::get('/data',  [DataController::class, 'index'])->name('data')->middleware('auth.admin');

Route::get('/forgot-password', [ForgetPassController::class, 'ForgetPass'])->name('forgot.password');

Route::post('/forgot-password', [ForgetPassController::class, 'ForgetPassPost'])->name('forgot.passwordpost');

Route::get('/reset-password/{token}', [ForgetPassController::class, 'ResetPass'])->name('reset.pass');

Route::post('/reset-password', [ForgetPassController::class, 'ResetPassPost'])->name('reset.passpost');

Route::get('/test-distance', [TestController::class, 'testDistance']);
