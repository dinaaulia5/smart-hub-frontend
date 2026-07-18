<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoomController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
});

Route::redirect('/', '/dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// DATA MASTER
Route::resource('categories', CategoryController::class);
Route::resource('rooms', RoomController::class);
Route::resource('equipments', EquipmentController::class);
Route::resource('bookings', BookingController::class);
