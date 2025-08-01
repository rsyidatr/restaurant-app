<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show waiter dashboard
     */
    public function dashboard()
    {
        $myTables = Table::where('assigned_waiter_id', Auth::id())
            ->with(['currentOrder', 'activeReservation'])
            ->get();

        $readyOrders = Order::where('status', 'ready')
            ->with(['table', 'orderItems.menuItem'])
            ->orderBy('ready_at', 'asc')
            ->get();

        $todayStats = [
            'orders_served' => Order::whereDate('created_at', today())
                ->where('served_by', Auth::id())
                ->count(),
            'tables_assigned' => $myTables->count(),
            'pending_service' => $readyOrders->count()
        ];

        return view('staff.waiter.dashboard', compact('myTables', 'readyOrders', 'todayStats'));
    }

    /**
     * Serve order to customer
     */
    public function serveOrder(Order $order)
    {
        if ($order->status === 'ready') {
            $order->update([
                'status' => 'served',
                'served_by' => Auth::id(),
                'served_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil disajikan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Status pesanan tidak valid'
        ]);
    }

    /**
     * Update table status
     */
    public function updateTableStatus(Table $table, Request $request)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved,cleaning'
        ]);

        $table->update([
            'status' => $request->status,
            'updated_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status meja berhasil diupdate'
        ]);
    }

    /**
     * Take new order
     */
    public function takeOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'special_requests' => 'nullable|string'
        ]);

        $table = Table::findOrFail($request->table_id);
        
        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . rand(100, 999),
            'table_id' => $table->id,
            'customer_name' => $request->customer_name,
            'order_type' => 'dine_in',
            'status' => 'pending',
            'special_requests' => $request->special_requests,
            'taken_by' => Auth::id(),
            'total_amount' => 0
        ]);

        $totalAmount = 0;

        // Add order items
        foreach ($request->items as $item) {
            $menuItem = \App\Models\MenuItem::findOrFail($item['menu_item_id']);
            $subtotal = $menuItem->price * $item['quantity'];
            
            $order->orderItems()->create([
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price' => $menuItem->price,
                'subtotal' => $subtotal
            ]);
            
            $totalAmount += $subtotal;
        }

        // Update order total
        $order->update(['total_amount' => $totalAmount]);

        // Update table status
        $table->update(['status' => 'occupied']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'order_id' => $order->id
        ]);
    }

    /**
     * Get my assigned tables
     */
    public function getMyTables()
    {
        $tables = Table::where('assigned_waiter_id', Auth::id())
            ->with(['currentOrder', 'activeReservation'])
            ->get();

        return response()->json([
            'success' => true,
            'tables' => $tables
        ]);
    }
}
