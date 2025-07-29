<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Routes
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/customer', function () {
        return view('customer.home');
    })->name('customer.home');
});

// Waiter Routes
Route::middleware(['auth', 'role:pelayan'])->group(function () {
    Route::get('/waiter', function () {
        return view('waiter.dashboard');
    })->name('waiter.dashboard');
});

// Kitchen Routes
Route::middleware(['auth', 'role:koki'])->group(function () {
    Route::get('/kitchen', function () {
        return view('kitchen.dashboard');
    })->name('kitchen.dashboard');
});
