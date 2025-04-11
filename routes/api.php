<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(UserAuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserAuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});