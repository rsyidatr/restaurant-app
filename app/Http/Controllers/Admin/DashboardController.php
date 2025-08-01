<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik ringkasan
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        // Statistik hari ini
        $todayRevenue = Order::whereDate('created_at', today())
                           ->where('payment_status', 'paid')
                           ->sum('total_amount');
        
        // Pesanan terbaru
        $recentOrders = Order::with(['user', 'table'])
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();
        
        // Status meja
        $tableStats = [
            'available' => Table::where('status', 'available')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'cleaning' => Table::where('status', 'cleaning')->count(),
        ];
        
        // Grafik penjualan mingguan
        $weeklyRevenue = Order::where('payment_status', 'paid')
                            ->where('created_at', '>=', now()->subDays(7))
                            ->groupBy(DB::raw('DATE(created_at)'))
                            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                            ->get();
        
        // Menu paling populer
        $popularMenus = DB::table('order_items')
                         ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                         ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_ordered'))
                         ->groupBy('menu_items.id', 'menu_items.name')
                         ->orderBy('total_ordered', 'desc')
                         ->take(5)
                         ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalOrders', 'totalRevenue', 'todayOrders',
            'todayRevenue', 'recentOrders', 'tableStats', 'weeklyRevenue', 'popularMenus'
        ));
    }
}
