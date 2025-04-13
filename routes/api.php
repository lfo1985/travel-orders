<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
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
        Route::patch('orders/{id}/status', 'updateStatus')->name('orders.updateStatus');
        Route::delete('orders/{id}', 'destroy')->name('orders.destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::get('users/{id}', 'show')->name('users.show');
        Route::post('users', 'store')->name('users.store');
        Route::put('users/{id}', 'update')->name('users.update');
        Route::delete('users/{id}', 'destroy')->name('users.destroy');
    });
});