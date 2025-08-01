<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Halaman checkout
    public function index()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        // Ambil cart items
        $cartItems = CartItem::forSessionOrUser($sessionId, $userId)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda kosong!');
        }
        
        // Hitung total
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        // Ambil meja yang tersedia
        $availableTables = Table::where('status', 'available')->get();
        
        return view('customer.checkout.index', compact('cartItems', 'total', 'availableTables'));
    }
    
    // Proses checkout
    public function process(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,transfer',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        // Ambil cart items
        $cartItems = CartItem::forSessionOrUser($sessionId, $userId)->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }
        
        // Hitung total
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        DB::beginTransaction();
        
        try {
            // Update status meja
            $table = Table::findOrFail($request->table_id);
            $table->update(['status' => 'occupied']);
            
            // Generate order number
            $orderNumber = Order::generateOrderNumber();
            
            // Buat order
            $order = Order::create([
                'order_number' => $orderNumber,
                'session_id' => $userId ? null : $sessionId,
                'user_id' => $userId,
                'table_id' => $request->table_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'order_type' => 'dine_in',
                'status' => 'pending',
                'payment_status' => 'pending',
                'total_amount' => $total,
                'tax_amount' => 0,
                'service_charge' => 0,
                'grand_total' => $total,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'order_date' => now()
            ]);
            
            // Buat order items dari cart
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'menu_name' => $cartItem->menu_name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'image' => $cartItem->image,
                    'notes' => $cartItem->notes
                ]);
            }
            
            // Clear cart
            $cartItems->each->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect' => route('checkout.receipt', $order->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat memproses order'], 500);
        }
    }
    
    // Halaman receipt
    public function receipt($orderId)
    {
        $order = Order::with(['orderItems', 'table'])->findOrFail($orderId);
        
        // Pastikan hanya pemilik order yang bisa akses (atau admin jika ada)
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        if ($userId && $order->user_id !== $userId) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        if (!$userId && $order->session_id !== $sessionId) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        return view('customer.checkout.receipt', compact('order'));
    }
}
