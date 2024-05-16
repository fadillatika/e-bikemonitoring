<?php

use App\Http\Controllers\GetrackingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\SearchController;


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

Route::get('/login', function () {
    return view('login', [
        "title" => "Login",
    ]);
})->name('login');

Route::get('/monitor', [MotorController::class, 'index'])->name('monitor');

Route::get('/search', [SearchController::class, 'search'])->name('search');