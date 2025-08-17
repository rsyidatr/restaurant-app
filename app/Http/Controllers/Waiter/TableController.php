<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with(['currentOrder.user'])
                      ->orderBy('table_number')
                      ->get();
        
        return view('waiter.tables.index', compact('tables'));
    }
    
    public function show(Table $table)
    {
        $table->load(['currentOrder.user', 'currentOrder.orderItems.menuItem']);
        
        return view('waiter.tables.show', compact('table'));
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
