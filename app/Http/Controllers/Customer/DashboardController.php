<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data untuk customer dashboard
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_reservations' => Reservation::where('user_id', $user->id)->count(),
            'upcoming_reservations' => Reservation::where('user_id', $user->id)
                                                ->where('status', 'confirmed')
                                                ->where('reservation_time', '>', now())
                                                ->count()
        ];
        
        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
                           ->with(['orderItems.menuItem', 'table'])
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();
        
        // Upcoming reservations
        $upcomingReservations = Reservation::where('user_id', $user->id)
                                         ->where('status', 'confirmed')
                                         ->where('reservation_time', '>', now())
                                         ->with('table')
                                         ->orderBy('reservation_time', 'asc')
                                         ->limit(3)
                                         ->get();
        
        // Popular menu items (for recommendations)
        $popularMenus = MenuItem::where('is_available', true)
                              ->withCount('orderItems')
                              ->orderBy('order_items_count', 'desc')
                              ->limit(6)
                              ->get();
        
        return view('customer.home.index', compact(
            'stats',
            'recentOrders', 
            'upcomingReservations',
            'popularMenus'
        ));
    }
}
