<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('result', [ResultController::class, 'currentLocation'])->name('result.currentLocation');
