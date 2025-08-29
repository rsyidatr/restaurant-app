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
        
        // Tampilkan semua pesanan yang perlu diproses dapur
        $query->whereIn('status', ['pending', 'preparing', 'ready']);
        
        // Filter berdasarkan tanggal - default hari ini
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default tampilkan pesanan hari ini
            $query->whereDate('created_at', now()->toDateString());
        }
        
        $orders = $query->orderBy('created_at', 'asc')->paginate(15);
        
        // Statistik untuk dashboard (tidak untuk filter)
        $todayDate = $request->filled('date') ? $request->date : now()->toDateString();
        $stats = [
            'pending' => Order::whereDate('created_at', $todayDate)->where('status', 'pending')->count(),
            'preparing' => Order::whereDate('created_at', $todayDate)->where('status', 'preparing')->count(),
            'ready' => Order::whereDate('created_at', $todayDate)->where('status', 'ready')->count(),
            'served' => Order::whereDate('created_at', $todayDate)->where('status', 'served')->count(),
        ];
        
        return view('kitchen.orders.index', compact('orders', 'stats'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'table', 'orderItems.menuItem']);
        
        return view('kitchen.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready'
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
        try {
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan sudah dalam proses atau selesai'
                ], 400);
            }

            $order->status = 'preparing';
            $order->started_cooking_at = now();
            
            if ($order->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan mulai dimasak',
                    'order_id' => $order->id,
                    'new_status' => 'preparing'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan status'
                ], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error starting cooking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markReady(Order $order)
    {
        try {
            if ($order->status !== 'preparing') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan belum dalam proses memasak'
                ], 400);
            }

            $order->status = 'ready';
            $order->ready_at = now();
            
            if ($order->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan siap disajikan',
                    'order_id' => $order->id,
                    'new_status' => 'ready'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan status'
                ], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error marking ready: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }    public function getOrderItems(Order $order)
    {
        $items = $order->orderItems()->with('menuItem')->get();
        
        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    }
}
