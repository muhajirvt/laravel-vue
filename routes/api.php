<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login');

    Route::middleware(['auth:api', 'superadmin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/current-user', [AuthController::class, 'currentUser'])->name('current-user');
    });
});

Route::middleware(['auth:api', 'superadmin'])->group(function () {
    Route::get('/users/{user}/avatar', [UserController::class, 'avatar'])->name('users.avatar');
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);
});
