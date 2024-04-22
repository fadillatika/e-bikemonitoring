<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\SearchController;


Route::get('/', function () {
    return view('home', [
        "title" => "Home"
    ]);
});

Route::get('/home', function () {
    return view('home', [
        "title" => "Home Page"
    ]);
});

Route::get('/warning', function () {
    return view('warning', [
        "title" => "Warning"
    ]);
});

Route::get('/about', function () {
    return view('about', [
        "title" => "About",
    ]);
});

Route::get('/dashboard', [MotorController::class, 'index']);

Route::get('/search', [SearchController::class, 'search'])->name('search');
