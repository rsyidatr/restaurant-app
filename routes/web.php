<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderHistoryController;
use App\Http\Controllers\CheckoutController;

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
    return view('customer.home');
})->name('home');

// Public Customer Routes (dapat diakses tanpa login)
Route::get('/menu', [MenuController::class, 'index'])->name('customer.menu');
Route::get('/cart', function() {
    return view('customer.cart');
})->name('customer.cart');
Route::get('/reservation', [ReservationController::class, 'index'])->name('customer.reservation');
Route::post('/reservation/preview', [ReservationController::class, 'preview'])->name('customer.reservation.preview');
Route::post('/reservation/store', [ReservationController::class, 'store'])->name('customer.reservation.store');

// Order History Routes (dapat diakses dengan atau tanpa login)
Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('customer.order-history');
Route::get('/order-history/{order}', [OrderHistoryController::class, 'show'])->name('customer.order-history.show');

// Cart Routes (dapat diakses dengan atau tanpa login)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::get('/get', [CartController::class, 'get'])->name('get');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Checkout Routes (dapat diakses dengan atau tanpa login)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/receipt/{order}', [CheckoutController::class, 'receipt'])->name('receipt');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Routes (perlu login)
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
