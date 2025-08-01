<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Daily revenue for last 30 days
        $dailyRevenue = Order::where('payment_status', 'paid')
                           ->where('created_at', '>=', now()->subDays(30))
                           ->groupBy(DB::raw('DATE(created_at)'))
                           ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                           ->orderBy('date')
                           ->get();
        
        // Monthly revenue for last 12 months
        $monthlyRevenue = Order::where('payment_status', 'paid')
                             ->where('created_at', '>=', now()->subMonths(12))
                             ->groupBy(DB::raw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)'))
                             ->selectRaw('EXTRACT(YEAR FROM created_at) as year, EXTRACT(MONTH FROM created_at) as month, SUM(total_amount) as total')
                             ->orderBy('year')
                             ->orderBy('month')
                             ->get();
        
        // Top selling items
        $topSellingItems = DB::table('order_items')
                            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->where('orders.payment_status', 'paid')
                            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price')
                            ->selectRaw('
                                menu_items.name,
                                menu_items.price,
                                SUM(order_items.quantity) as total_quantity,
                                SUM(order_items.quantity * order_items.price) as total_revenue
                            ')
                            ->orderBy('total_quantity', 'desc')
                            ->take(10)
                            ->get();
        
        // Order type distribution
        $orderTypeStats = Order::where('payment_status', 'paid')
                             ->groupBy('order_type')
                             ->selectRaw('order_type, COUNT(*) as count, SUM(total_amount) as revenue')
                             ->get();
        
        return view('admin.reports.index', compact(
            'dailyRevenue', 'monthlyRevenue', 'topSellingItems', 'orderTypeStats'
        ));
    }
    
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $salesData = Order::with(['user', 'orderItems.menuItem'])
                         ->where('payment_status', 'paid')
                         ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
        
        $summary = [
            'total_orders' => $salesData->total(),
            'total_revenue' => Order::where('payment_status', 'paid')
                                  ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                                  ->sum('total_amount'),
            'average_order' => Order::where('payment_status', 'paid')
                                  ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                                  ->avg('total_amount'),
        ];
        
        return view('admin.reports.sales', compact('salesData', 'summary', 'startDate', 'endDate'));
    }
    
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $type = $request->get('type', 'sales'); // sales, menu, payments
        
        // This would typically export to Excel/CSV
        // For now, return JSON data
        switch ($type) {
            case 'sales':
                $data = Order::with(['user', 'orderItems.menuItem'])
                           ->where('payment_status', 'paid')
                           ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                           ->get();
                break;
                
            case 'menu':
                $data = DB::table('order_items')
                         ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                         ->join('orders', 'order_items.order_id', '=', 'orders.id')
                         ->where('orders.payment_status', 'paid')
                         ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate])
                         ->groupBy('menu_items.id', 'menu_items.name')
                         ->selectRaw('
                             menu_items.name,
                             SUM(order_items.quantity) as total_quantity,
                             SUM(order_items.quantity * order_items.price) as total_revenue
                         ')
                         ->orderBy('total_quantity', 'desc')
                         ->get();
                break;
                
            case 'payments':
                $data = Payment::with('order')
                             ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                             ->get();
                break;
                
            default:
                $data = [];
        }
        
        return response()->json([
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data
        ]);
    }
}
