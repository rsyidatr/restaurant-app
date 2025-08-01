<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!in_array(Auth::user()->role, ['waiter', 'admin'])) {
                abort(403, 'Akses tidak diizinkan.');
            }
            return $next($request);
        });
    }

    /**
     * Show the waiter dashboard.
     */
    public function dashboard()
    {
        return view('waiter.dashboard');
    }

    /**
     * Get table status
     */
    public function getTableStatus()
    {
        // This would typically fetch from database
        $tables = [];
        for ($i = 1; $i <= 12; $i++) {
            $tables[] = [
                'number' => $i,
                'status' => $i <= 3 ? 'occupied' : ($i <= 6 ? 'reserved' : 'available'),
                'order_id' => $i <= 6 ? rand(100, 999) : null,
                'customer_count' => $i <= 6 ? rand(1, 4) : 0,
            ];
        }

        return response()->json($tables);
    }

    /**
     * Get recent orders
     */
    public function getRecentOrders()
    {
        // This would typically fetch from database
        $orders = [
            [
                'id' => 1,
                'table_number' => 3,
                'items' => ['Nasi Gudeg', 'Es Teh Manis x2'],
                'status' => 'preparing',
                'created_at' => now()->subMinutes(5),
                'total' => 25000
            ],
            [
                'id' => 2,
                'table_number' => 7,
                'items' => ['Sate Ayam', 'Nasi Putih', 'Es Jeruk'],
                'status' => 'ready',
                'created_at' => now()->subMinutes(12),
                'total' => 30000
            ],
            [
                'id' => 3,
                'table_number' => 1,
                'items' => ['Gado-gado', 'Kerupuk', 'Es Campur'],
                'status' => 'serving',
                'created_at' => now()->subMinutes(18),
                'total' => 22000
            ]
        ];

        return response()->json($orders);
    }

    /**
     * Create new order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'table_number' => 'required|integer|min:1|max:12',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string'
        ]);

        // This would typically save to database
        $order = [
            'id' => rand(100, 999),
            'table_number' => $request->table_number,
            'items' => $request->items,
            'status' => 'new',
            'created_at' => now(),
            'total' => count($request->items) * 15000 // Simple calculation
        ];

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'order' => $order
        ]);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:preparing,ready,serving,completed,cancelled'
        ]);

        // This would typically update database
        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diupdate'
        ]);
    }

    /**
     * Mark order as served
     */
    public function markAsServed($tableNumber)
    {
        // This would typically update database
        return response()->json([
            'success' => true,
            'message' => "Pesanan meja {$tableNumber} ditandai sudah diantar"
        ]);
    }

    /**
     * Process payment for table
     */
    public function processPayment(Request $request, $tableNumber)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,qr'
        ]);

        // This would typically process payment and update database
        return response()->json([
            'success' => true,
            'message' => "Pembayaran meja {$tableNumber} berhasil diproses"
        ]);
    }
}
