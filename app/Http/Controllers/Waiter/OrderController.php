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
        
        // Get stats for today's orders
        $stats = [
            'pending' => Order::where('order_type', 'dine_in')
                             ->where('status', 'pending')
                             ->whereDate('created_at', today())
                             ->count(),
            'preparing' => Order::where('order_type', 'dine_in')
                               ->where('status', 'preparing')
                               ->whereDate('created_at', today())
                               ->count(),
            'ready' => Order::where('order_type', 'dine_in')
                           ->where('status', 'ready')
                           ->whereDate('created_at', today())
                           ->count(),
            'served' => Order::where('order_type', 'dine_in')
                            ->where('status', 'served')
                            ->whereDate('created_at', today())
                            ->count(),
        ];
        
        return view('waiter.orders.index', compact('orders', 'tables', 'stats'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'table', 'orderItems.menuItem', 'payment']);
        
        return view('waiter.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Only allow editing for certain statuses
        if (!in_array($order->status, ['pending', 'preparing'])) {
            return redirect()->route('waiter.orders.index')
                           ->with('error', 'Pesanan dengan status ' . $order->status . ' tidak dapat diedit.');
        }

        $order->load(['user', 'table', 'orderItems.menuItem']);
        
        // Get available tables for table change if needed
        try {
            $tables = Table::where('status', 'available')
                          ->orWhere('id', $order->table_id)
                          ->orderBy('table_number')
                          ->get();
        } catch (\Exception $e) {
            $tables = collect();
        }

        return view('waiter.orders.edit', compact('order', 'tables'));
    }

    public function update(Request $request, Order $order)
    {
        // Only allow updating for certain statuses
        if (!in_array($order->status, ['pending', 'preparing'])) {
            return redirect()->route('waiter.orders.index')
                           ->with('error', 'Pesanan dengan status ' . $order->status . ' tidak dapat diupdate.');
        }

        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'notes' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        try {
            $order->update([
                'table_id' => $request->table_id,
                'notes' => $request->notes,
                'special_instructions' => $request->special_instructions,
            ]);

            return redirect()->route('waiter.orders.show', $order)
                           ->with('success', 'Pesanan berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal mengupdate pesanan: ' . $e->getMessage())
                           ->withInput();
        }
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
