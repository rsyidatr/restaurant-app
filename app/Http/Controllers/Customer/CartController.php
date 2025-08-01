<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getSessionId(Request $request)
    {
        if (!$request->session()->has('cart_session_id')) {
            $request->session()->put('cart_session_id', uniqid('cart_', true));
        }
        return $request->session()->get('cart_session_id');
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|integer',
            'menu_name' => 'required|string',
            'price' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|string'
        ]);

        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        // Cek apakah item sudah ada di cart
        $existingItem = CartItem::forSessionOrUser($sessionId, $userId)
            ->where('menu_item_id', $request->menu_item_id)
            ->first();

        if ($existingItem) {
            // Update quantity jika item sudah ada
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
            $cartItem = $existingItem;
        } else {
            // Buat item baru jika belum ada
            $cartItem = CartItem::create([
                'session_id' => $userId ? null : $sessionId,
                'user_id' => $userId,
                'menu_item_id' => $request->menu_item_id,
                'menu_name' => $request->menu_name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'image' => $request->image
            ]);
        }

        // Hitung total items di cart
        $totalItems = CartItem::forSessionOrUser($sessionId, $userId)->sum('quantity');
        $totalAmount = CartItem::forSessionOrUser($sessionId, $userId)->get()->sum('total');

        return response()->json([
            'success' => true,
            'message' => $request->menu_name . ' ditambahkan ke keranjang!',
            'cart_count' => $totalItems,
            'cart_total' => $totalAmount,
            'item' => $cartItem
        ]);
    }

    public function get(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        $cartItems = CartItem::forSessionOrUser($sessionId, $userId)->get();
        $totalItems = $cartItems->sum('quantity');
        $totalAmount = $cartItems->sum('total');

        return response()->json([
            'items' => $cartItems,
            'cart_count' => $totalItems,
            'cart_total' => $totalAmount
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        $cartItem = CartItem::forSessionOrUser($sessionId, $userId)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        $totalItems = CartItem::forSessionOrUser($sessionId, $userId)->sum('quantity');
        $totalAmount = CartItem::forSessionOrUser($sessionId, $userId)->get()->sum('total');

        return response()->json([
            'success' => true,
            'cart_count' => $totalItems,
            'cart_total' => $totalAmount,
            'item' => $cartItem
        ]);
    }

    public function remove(Request $request, $id)
    {
        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        $cartItem = CartItem::forSessionOrUser($sessionId, $userId)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        $totalItems = CartItem::forSessionOrUser($sessionId, $userId)->sum('quantity');
        $totalAmount = CartItem::forSessionOrUser($sessionId, $userId)->get()->sum('total');

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
            'cart_count' => $totalItems,
            'cart_total' => $totalAmount
        ]);
    }

    public function clear(Request $request)
    {
        $sessionId = $this->getSessionId($request);
        $userId = Auth::id();

        CartItem::forSessionOrUser($sessionId, $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }
}
