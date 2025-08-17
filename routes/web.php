<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\MultiSessionController;
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
    
    // Test login route
    Route::get('/login-test', function () {
        return view('auth.login_test');
    })->name('login.test');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Debug route untuk test login pelayan
Route::get('/test-waiter', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return view('waiter.dashboard_test', [
            'todayOrders' => 5,
            'pendingOrders' => 3,
            'processingOrders' => 2,
            'availableTables' => 8,
            'occupiedTables' => 4,
            'todayReservations' => 6,
            'recentOrders' => collect([]),
            'stats' => [],
            'tables' => collect([])
        ]);
    } else {
        return redirect()->route('login')->with('error', 'Please login first');
    }
})->name('test.waiter');

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

// Waiter Routes
Route::middleware(['auth', 'role:pelayan'])->prefix('waiter')->name('waiter.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Waiter\DashboardController::class, 'index'])->name('dashboard');
    
    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Waiter\OrderController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Waiter\OrderController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Waiter\OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [\App\Http\Controllers\Waiter\OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [\App\Http\Controllers\Waiter\OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [\App\Http\Controllers\Waiter\OrderController::class, 'update'])->name('update');
        Route::put('/{order}/status', [\App\Http\Controllers\Waiter\OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{order}/confirm', [\App\Http\Controllers\Waiter\OrderController::class, 'confirmOrder'])->name('confirm');
        Route::post('/{order}/mark-served', [\App\Http\Controllers\Waiter\OrderController::class, 'markAsServed'])->name('mark-served');
        Route::post('/{order}/confirm-received', [\App\Http\Controllers\Waiter\OrderController::class, 'confirmReceived'])->name('confirmReceived');
        Route::get('/{order}/items', [\App\Http\Controllers\Waiter\OrderController::class, 'getOrderItems'])->name('items');
        Route::get('/{order}/receipt', [\App\Http\Controllers\Waiter\OrderController::class, 'receipt'])->name('receipt');
    });
    
    // Table Management
    Route::prefix('tables')->name('tables.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Waiter\TableController::class, 'index'])->name('index');
        Route::get('/{table}', [\App\Http\Controllers\Waiter\TableController::class, 'show'])->name('show');
        Route::put('/{table}/status', [\App\Http\Controllers\Waiter\TableController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{table}/quick-view', [\App\Http\Controllers\Waiter\TableController::class, 'quickView'])->name('quickView');
    });
    
    // Reservation Management
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Waiter\ReservationController::class, 'index'])->name('index');
        Route::get('/{reservation}', [\App\Http\Controllers\Waiter\ReservationController::class, 'show'])->name('show');
        Route::put('/{reservation}/status', [\App\Http\Controllers\Waiter\ReservationController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{reservation}/assign-table', [\App\Http\Controllers\Waiter\ReservationController::class, 'assignTable'])->name('assignTable');
        Route::post('/{reservation}/check-in', [\App\Http\Controllers\Waiter\ReservationController::class, 'checkIn'])->name('checkIn');
        Route::post('/{reservation}/cancel', [\App\Http\Controllers\Waiter\ReservationController::class, 'cancel'])->name('cancel');
    });
});

// Kitchen Routes
Route::middleware(['auth', 'role:koki'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kitchen\DashboardController::class, 'index'])->name('dashboard');
    
    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Kitchen\KitchenOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Kitchen\KitchenOrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [\App\Http\Controllers\Kitchen\KitchenOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{order}/start-cooking', [\App\Http\Controllers\Kitchen\KitchenOrderController::class, 'startCooking'])->name('startCooking');
        Route::post('/{order}/mark-ready', [\App\Http\Controllers\Kitchen\KitchenOrderController::class, 'markReady'])->name('markReady');
        Route::get('/{order}/items', [\App\Http\Controllers\Kitchen\KitchenOrderController::class, 'getOrderItems'])->name('items');
    });
    
    // Menu Availability Management
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Kitchen\MenuAvailabilityController::class, 'index'])->name('index');
        Route::get('/{menuItem}', [\App\Http\Controllers\Kitchen\MenuAvailabilityController::class, 'show'])->name('show');
        Route::post('/{menuItem}/toggle-availability', [\App\Http\Controllers\Kitchen\MenuAvailabilityController::class, 'toggleAvailability'])->name('toggleAvailability');
        Route::post('/bulk-update', [\App\Http\Controllers\Kitchen\MenuAvailabilityController::class, 'bulkUpdateAvailability'])->name('bulkUpdate');
    });
});

// Customer Routes (perlu login)
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/customer', function () {
        return view('customer.home');
    })->name('customer.home');
});

// Waiter Routes (Legacy - redirect to new routes)
Route::middleware(['auth', 'role:pelayan'])->group(function () {
    Route::get('/waiter', function () {
        return redirect()->route('waiter.dashboard');
    })->name('waiter.dashboard.legacy');
});

// Kitchen Routes (Legacy - redirect to new routes)
Route::middleware(['auth', 'role:koki'])->group(function () {
    Route::get('/kitchen', function () {
        return redirect()->route('kitchen.dashboard');
    })->name('kitchen.dashboard.legacy');
});

// Debug Routes untuk troubleshooting waiter login
Route::get('/debug-auth', function () {
    $user = Auth::user();
    
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ] : null,
        'waiter_routes' => [
            'dashboard' => route('waiter.dashboard'),
            'orders' => route('waiter.orders.index'),
        ]
    ]);
});

Route::get('/debug-waiter-direct', function () {
    // Test view waiter langsung tanpa middleware
    try {
        return view('waiter.dashboard', [
            'todayOrders' => 5,
            'pendingOrders' => 2,
            'processingOrders' => 3,
            'availableTables' => 8,
            'recentOrders' => collect([]),
            'reservations' => collect([])
        ]);
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
