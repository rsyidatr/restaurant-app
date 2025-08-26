<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'table', 'orderItems.menuItem'])
                     ->where('order_type', 'dine_in');
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Filter berdasarkan table
        if ($request->filled('table_id')) {
            $query->where('table_id', $request->table_id);
        }
        
        // Filter berdasarkan search (order number)
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get all tables for the filter dropdown
        try {
            $tables = Table::orderBy('table_number')->get();
        } catch (\Exception $e) {
            $tables = collect(); // Return empty collection if table model fails
        }
        
        return view('waiter.orders.index', compact('orders', 'tables'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'table', 'orderItems.menuItem', 'payment']);
        
        return view('waiter.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();
        
        // Update status meja jika pesanan completed
        if ($request->status === 'completed' && $order->table) {
            $order->table->status = 'cleaning';
            $order->table->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => "Status pesanan berhasil diubah dari {$oldStatus} ke {$request->status}",
            'new_status' => $request->status
        ]);
    }
    
    public function confirmReceived(Order $order)
    {
        if ($order->order_type !== 'dine_in') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pesanan dine-in yang dapat dikonfirmasi'
            ], 400);
        }
        
        $order->status = 'completed';
        $order->save();
        
        // Update status meja
        if ($order->table) {
            $order->table->status = 'cleaning';
            $order->table->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan telah dikonfirmasi diterima pelanggan'
        ]);
    }
    
    public function getOrderItems(Order $order)
    {
        $items = $order->orderItems()->with('menuItem')->get();
        
        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    }

    public function confirmOrder(Order $order)
    {
        try {
            $order->update([
                'status' => 'preparing',
                'confirmed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi dan dikirim ke dapur'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pesanan'
            ]);
        }
    }

    public function markAsServed(Order $order)
    {
        try {
            $order->update([
                'status' => 'served',
                'served_at' => now()
            ]);

            // Update table status jika ada
            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditandai sebagai disajikan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai pesanan sebagai disajikan'
            ]);
        }
    }

    public function receipt(Order $order)
    {
        return view('waiter.orders.receipt', compact('order'));
    }
}
