<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('table_number')->paginate(12);
        
        $statusCounts = [
            'available' => Table::where('status', 'available')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'cleaning' => Table::where('status', 'cleaning')->count(),
        ];
        
        return view('admin.tables.index', compact('tables', 'statusCounts'));
    }
    
    public function create()
    {
        return view('admin.tables.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|integer|unique:tables',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,reserved,occupied,cleaning'
        ]);
        
        Table::create($validated);
        
        return redirect()->route('admin.tables.index')->with('success', 'Table created successfully.');
    }
    
    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }
    
    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|integer|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,reserved,occupied,cleaning'
        ]);
        
        $table->update($validated);
        
        return redirect()->route('admin.tables.index')->with('success', 'Table updated successfully.');
    }
    
    public function destroy(Table $table)
    {
        // Check if table has active reservations
        if ($table->reservations()->where('status', '!=', 'cancelled')->exists()) {
            return redirect()->route('admin.tables.index')->with('error', 'Cannot delete table with active reservations.');
        }
        
        $table->delete();
        
        return redirect()->route('admin.tables.index')->with('success', 'Table deleted successfully.');
    }
    
    public function updateStatus(Request $request, Table $table)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,reserved,occupied,cleaning'
        ]);
        
        $table->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Table status updated successfully',
            'status' => $table->status
        ]);
    }
}
