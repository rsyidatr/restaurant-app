<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $query = Table::with(['currentOrder.user']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tables = $query->orderBy('table_number')->paginate(12);
        
        return view('waiter.tables.index', compact('tables'));
    }
    
    public function show(Table $table)
    {
        $table->load(['currentOrder.user', 'currentOrder.orderItems']);
        
        // Get today's orders for this table
        $todayOrders = Order::where('table_id', $table->id)
                           ->whereDate('created_at', today())
                           ->with(['orderItems'])
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('waiter.tables.show', compact('table', 'todayOrders'));
    }
    
    public function updateStatus(Request $request, Table $table)
    {
        $request->validate([
            'status' => 'required|in:available,reserved,occupied,cleaning'
        ]);
        
        $oldStatus = $table->status;
        $table->status = $request->status;
        $table->save();
        
        return response()->json([
            'success' => true,
            'message' => "Status meja {$table->table_number} berhasil diubah dari {$oldStatus} ke {$request->status}",
            'new_status' => $request->status
        ]);
    }
    
    public function quickView(Table $table)
    {
        $table->load(['currentOrder.user', 'currentOrder.orderItems.menuItem']);
        
        return response()->json([
            'success' => true,
            'table' => $table
        ]);
    }
}
