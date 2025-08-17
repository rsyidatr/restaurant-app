<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk dashboard pelayan
        $todayOrders = Order::whereDate('created_at', Carbon::today())
                           ->where('order_type', 'dine_in')
                           ->count();
        
        $pendingOrders = Order::where('status', 'pending')
                             ->where('order_type', 'dine_in')
                             ->count();
        
        $processingOrders = Order::where('status', 'preparing')
                                ->where('order_type', 'dine_in')
                                ->count();
        
        $availableTables = Table::where('status', 'available')->count();
        $occupiedTables = Table::where('status', 'occupied')->count();
        
        $todayReservations = Reservation::whereDate('reservation_time', Carbon::today())
                                      ->where('status', '!=', 'cancelled')
                                      ->count();
        
        // Recent orders untuk dine-in
        $recentOrders = Order::with(['table', 'orderItems.menuItem'])
                            ->where('order_type', 'dine_in')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        // Jika tidak ada data, set default
        $stats = [
            'pending' => $pendingOrders,
            'preparing' => $processingOrders,
            'ready' => Order::where('status', 'ready')->where('order_type', 'dine_in')->count(),
            'served' => Order::where('status', 'served')->where('order_type', 'dine_in')->count()
        ];

        $tables = Table::all();
        
        return view('waiter.dashboard', compact(
            'todayOrders',
            'pendingOrders', 
            'processingOrders',
            'availableTables',
            'occupiedTables',
            'todayReservations',
            'recentOrders',
            'stats',
            'tables'
        ));
    }
}
