<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        // Get all orders for this user (both authenticated and guest sessions)
        $orders = Order::with(['orderItems', 'table'])
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId)
                          ->orWhere('session_id', $sessionId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('customer.order-history', compact('orders'));
    }
    
    public function show($orderId)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $order = Order::with(['orderItems', 'table'])
            ->where('id', $orderId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId)
                          ->orWhere('session_id', $sessionId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->firstOrFail();
        
        return view('customer.order-detail', compact('order'));
    }
}
