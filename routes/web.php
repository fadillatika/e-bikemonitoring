<?php

use App\Http\Controllers\GetrackingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\SearchController;

// Menggabungkan home dan /home ke satu rute
Route::redirect('/home', '/');

Route::get('/', function () {
    return view('home', [
        "title" => "Home"
    ]);
})->name('home');

Route::get('/warning', function () {
    return view('warning', [
        "title" => "Warning"
    ]);
})->name('warning');

Route::get('/about', function () {
    return view('about', [
        "title" => "About",
    ]);
})->name('about');

Route::get('/dashboard', [MotorController::class, 'index'])->name('dashboard');

Route::get('/search', [SearchController::class, 'search'])->name('search');