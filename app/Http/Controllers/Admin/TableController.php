<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        // Get all tables ordered by table number for layout display
        $tables = Table::orderByRaw("
            CASE 
                WHEN table_number ~ '^[0-9]+$' THEN CAST(table_number AS INTEGER)
                ELSE 999 
            END, 
            table_number
        ")->get();
        
        $statusCounts = [
            'available' => Table::where('status', 'available')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'cleaning' => Table::where('status', 'cleaning')->count(),
        ];
        
        // Additional stats for the dashboard
        $stats = [
            'available' => $statusCounts['available'],
            'occupied' => $statusCounts['occupied'],
            'reserved' => $statusCounts['reserved'],
            'maintenance' => $statusCounts['cleaning'],
            'total' => Table::count()
        ];
        
        return view('admin.tables.index', compact('tables', 'statusCounts', 'stats'));
    }
    
    public function create()
    {
        return view('admin.tables.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|integer|unique:tables',
            'capacity' => 'required|integer|in:2,4,6,8,10,15,20,30',
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
            'capacity' => 'required|integer|in:2,4,6,8,10,15,20,30',
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
