<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChefController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chef dashboard
     */
    public function dashboard()
    {
        $pendingOrders = Order::whereIn('status', ['confirmed', 'preparing'])
            ->with(['orderItems.menuItem', 'table'])
            ->orderBy('created_at', 'asc')
            ->get();

        $todayStats = [
            'orders_completed' => Order::whereDate('created_at', today())
                ->where('status', 'ready')
                ->count(),
            'orders_pending' => Order::whereIn('status', ['confirmed', 'preparing'])
                ->count(),
            'total_items' => OrderItem::whereHas('order', function($q) {
                $q->whereDate('created_at', today());
            })->sum('quantity')
        ];

        return view('staff.chef.dashboard', compact('pendingOrders', 'todayStats'));
    }

    /**
     * Update order status to preparing
     */
    public function startPreparing(Order $order)
    {
        if ($order->status === 'confirmed') {
            $order->update([
                'status' => 'preparing',
                'prepared_by' => Auth::id(),
                'preparation_started_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan mulai diproses'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Status pesanan tidak valid'
        ]);
    }

    /**
     * Mark order as ready
     */
    public function markReady(Order $order)
    {
        if ($order->status === 'preparing') {
            $order->update([
                'status' => 'ready',
                'ready_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan siap disajikan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Status pesanan tidak valid'
        ]);
    }

    /**
     * Get order details
     */
    public function getOrderDetails(Order $order)
    {
        $order->load(['orderItems.menuItem', 'table', 'user']);
        
        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
}
