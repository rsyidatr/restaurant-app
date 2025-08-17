<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class KitchenOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'table', 'orderItems.menuItem']);
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default tampilkan yang perlu diproses dapur
            $query->whereIn('status', ['pending', 'processing', 'ready']);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $orders = $query->orderBy('created_at', 'asc')->paginate(15);
        
        return view('kitchen.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'table', 'orderItems.menuItem']);
        
        return view('kitchen.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready'
        ]);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => "Status pesanan berhasil diubah dari {$oldStatus} ke {$request->status}",
            'new_status' => $request->status
        ]);
    }
    
    public function startCooking(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan sudah dalam proses atau selesai'
            ], 400);
        }
        
        $order->status = 'processing';
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan mulai dimasak'
        ]);
    }
    
    public function markReady(Order $order)
    {
        if ($order->status !== 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan belum dalam proses memasak'
            ], 400);
        }
        
        $order->status = 'ready';
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan siap disajikan'
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
}
