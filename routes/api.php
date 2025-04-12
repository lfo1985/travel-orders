<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(UserAuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserAuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'index')->name('orders.index');
        Route::get('orders/{id}', 'show')->name('orders.show');
        Route::post('orders', 'store')->name('orders.store');
        Route::put('orders/{id}', 'update')->name('orders.update');
        Route::delete('orders/{id}', 'destroy')->name('orders.destroy');
    });
});