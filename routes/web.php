<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\MultiSessionController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderHistoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\WaiterController;

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

// Multi-Session Routes
Route::prefix('multi-session')->name('multi-session.')->group(function () {
    Route::get('/', [MultiSessionController::class, 'dashboard'])->name('dashboard');
    Route::post('/login/{guard}', [MultiSessionController::class, 'loginWithGuard'])->name('login');
    Route::post('/logout/{guard}', [MultiSessionController::class, 'logoutFromGuard'])->name('logout');
    Route::get('/switch/{guard}', [MultiSessionController::class, 'switchSession'])->name('switch');
});

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

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Menu Category Management
    Route::prefix('menu-categories')->name('menu-categories.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'store'])->name('store');
        Route::get('/{menuCategory}', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'show'])->name('show');
        Route::get('/{menuCategory}/edit', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'edit'])->name('edit');
        Route::put('/{menuCategory}', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'update'])->name('update');
        Route::delete('/{menuCategory}', [\App\Http\Controllers\Admin\MenuCategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Menu Management
    Route::resource('menu', AdminMenuController::class);
    Route::post('menu/{menuItem}/toggle-availability', [AdminMenuController::class, 'toggleAvailability'])->name('menu.toggle-availability');
    
    // Order Management
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'destroy']);
    Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/items', [AdminOrderController::class, 'getOrderItems'])->name('orders.items');
    Route::get('orders/{order}/print', [AdminOrderController::class, 'print'])->name('orders.print');
    Route::get('orders/{order}/print-kitchen', [AdminOrderController::class, 'printKitchen'])->name('orders.printKitchen');
    
    // Reservation Management
    Route::resource('reservations', AdminReservationController::class);
    Route::put('reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
    Route::put('reservations/{reservation}/assign-table', [AdminReservationController::class, 'assignTable'])->name('reservations.assignTable');
    Route::post('reservations/{reservation}/confirm', [AdminReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('reservations/{reservation}/cancel', [AdminReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('reservations/{reservation}/check-in', [AdminReservationController::class, 'checkIn'])->name('reservations.checkIn');
    
    // Table Management
    Route::resource('tables', TableController::class);
    Route::put('tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.updateStatus');
    Route::post('tables/{table}/toggle-availability', [TableController::class, 'toggleAvailability'])->name('tables.toggleAvailability');
    Route::get('tables/{table}/quick-view', [TableController::class, 'quickView'])->name('tables.quickView');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::put('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    
    // Payment Management
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::put('payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::post('payments/{payment}/refund', [PaymentController::class, 'processRefund'])->name('payments.refund');
    Route::get('payments/report', [PaymentController::class, 'report'])->name('payments.report');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/tables', [ReportController::class, 'tables'])->name('tables');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });
});

// Customer Routes (perlu login)
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/customer', function () {
        return view('customer.home');
    })->name('customer.home');
});

// Waiter Routes
Route::middleware(['auth', 'role:pelayan'])->group(function () {
    Route::get('/waiter', [WaiterController::class, 'dashboard'])->name('waiter.dashboard');
    
    // Waiter API endpoints
    Route::get('/waiter/tables', [WaiterController::class, 'getTableStatus'])->name('waiter.tables');
    Route::get('/waiter/orders', [WaiterController::class, 'getRecentOrders'])->name('waiter.orders');
    Route::post('/waiter/orders', [WaiterController::class, 'createOrder'])->name('waiter.orders.create');
    Route::patch('/waiter/orders/{order}', [WaiterController::class, 'updateOrderStatus'])->name('waiter.orders.update');
    Route::post('/waiter/tables/{table}/served', [WaiterController::class, 'markAsServed'])->name('waiter.tables.served');
    Route::post('/waiter/tables/{table}/payment', [WaiterController::class, 'processPayment'])->name('waiter.tables.payment');
});

// Kitchen Routes
Route::middleware(['auth', 'role:koki'])->group(function () {
    Route::get('/kitchen', function () {
        return view('kitchen.dashboard');
    })->name('kitchen.dashboard');
    Route::get('/chef', [ChefController::class, 'dashboard'])->name('chef.dashboard');
    
    // Chef API endpoints
    Route::get('/chef/orders', [ChefController::class, 'getOrders'])->name('chef.orders');
    Route::patch('/chef/orders/{order}/status', [ChefController::class, 'updateOrderStatus'])->name('chef.orders.status');
    Route::post('/chef/orders/{order}/priority', [ChefController::class, 'setPriority'])->name('chef.orders.priority');
});

// Staff Authentication Routes
Route::group(['prefix' => 'staff'], function () {
    Route::get('/', function () {
        return view('staff.index');
    })->name('staff.index');
    
    Route::get('login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
    Route::post('login', [StaffLoginController::class, 'login'])->name('staff.login.submit');
    Route::post('logout', [StaffLoginController::class, 'logout'])->name('staff.logout');
    
    // Staff Dashboard Routes (require staff authentication)
    Route::middleware(['auth', 'role:admin,chef,waiter'])->group(function () {
        Route::get('dashboard', function () {
            $role = session('current_role', Auth::user()->role);
            
            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'chef':
                    return redirect()->route('chef.dashboard');
                case 'waiter':
                    return redirect()->route('waiter.dashboard');
                default:
                    return redirect()->route('staff.login');
            }
        })->name('staff.dashboard');
    });
});
