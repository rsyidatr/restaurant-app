<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk dashboard koki
        $todayOrders = Order::whereDate('created_at', Carbon::today())
                           ->whereIn('status', ['pending', 'processing'])
                           ->count();
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $readyOrders = Order::where('status', 'ready')->count();
        
        $availableMenus = MenuItem::where('is_available', true)->count();
        $unavailableMenus = MenuItem::where('is_available', false)->count();
        
        // Recent orders yang perlu diproses
        $recentOrders = Order::with(['user', 'table', 'orderItems.menuItem'])
                            ->whereIn('status', ['pending', 'processing'])
                            ->orderBy('created_at', 'asc')
                            ->take(10)
                            ->get();
        
        // Menu items yang perlu perhatian (stok habis atau hampir habis)
        $menuAlerts = MenuItem::where('is_available', false)
                             ->with('category')
                             ->take(5)
                             ->get();
        
        return view('kitchen.dashboard', compact(
            'todayOrders',
            'pendingOrders',
            'processingOrders', 
            'readyOrders',
            'availableMenus',
            'unavailableMenus',
            'recentOrders',
            'menuAlerts'
        ));
    }
}
