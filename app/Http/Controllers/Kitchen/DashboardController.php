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
        // Data untuk dashboard koki - hanya pesanan hari ini
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        
        // Pesanan hari ini berdasarkan status
        $pendingOrders = Order::whereDate('created_at', Carbon::today())
                             ->where('status', 'pending')->count();
        $processingOrders = Order::whereDate('created_at', Carbon::today())
                                ->where('status', 'preparing')->count();
        $readyOrders = Order::whereDate('created_at', Carbon::today())
                           ->where('status', 'ready')->count();
        $completedOrders = Order::whereDate('created_at', Carbon::today())
                                ->where('status', 'completed')->count();
        
        $availableMenus = MenuItem::where('is_available', true)->count();
        $unavailableMenus = MenuItem::where('is_available', false)->count();
        
        // Pesanan masuk hari ini yang perlu diproses (urut berdasarkan waktu masuk)
        $recentOrders = Order::with(['user', 'table', 'orderItems.menuItem'])
                            ->whereDate('created_at', Carbon::today())
                            ->whereIn('status', ['pending', 'preparing', 'ready'])
                            ->orderBy('created_at', 'asc')
                            ->take(15)
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
            'completedOrders',
            'availableMenus',
            'unavailableMenus',
            'recentOrders',
            'menuAlerts'
        ));
    }
}
