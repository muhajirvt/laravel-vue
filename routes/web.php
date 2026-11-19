<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/login', 'welcome')->name('login');
Route::view('/dashboard', 'welcome')->name('dashboard');
