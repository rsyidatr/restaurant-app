<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'table']);
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('reservation_time', $request->date);
        } else {
            // Default tampilkan reservasi hari ini dan yang akan datang
            $query->whereDate('reservation_time', '>=', Carbon::today());
        }
        
        $reservations = $query->orderBy('reservation_time', 'asc')->paginate(15);
        
        return view('waiter.reservations.index', compact('reservations'));
    }
    
    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'table']);
        
        return view('waiter.reservations.show', compact('reservation'));
    }
    
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);
        
        $oldStatus = $reservation->status;
        $reservation->status = $request->status;
        $reservation->save();
        
        return response()->json([
            'success' => true,
            'message' => "Status reservasi berhasil diubah dari {$oldStatus} ke {$request->status}",
            'new_status' => $request->status
        ]);
    }
    
    public function assignTable(Request $request, Reservation $reservation)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id'
        ]);
        
        $table = Table::find($request->table_id);
        
        if ($table->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Meja tidak tersedia'
            ], 400);
        }
        
        $reservation->table_id = $request->table_id;
        $reservation->status = 'confirmed';
        $reservation->save();
        
        $table->status = 'reserved';
        $table->save();
        
        return response()->json([
            'success' => true,
            'message' => "Meja {$table->table_number} berhasil ditugaskan untuk reservasi"
        ]);
    }
    
    public function checkIn(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi belum dikonfirmasi'
            ], 400);
        }
        
        $reservation->status = 'completed';
        $reservation->save();
        
        if ($reservation->table) {
            $reservation->table->status = 'occupied';
            $reservation->table->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Pelanggan berhasil check-in'
        ]);
    }
    
    public function cancel(Reservation $reservation)
    {
        $reservation->status = 'cancelled';
        $reservation->save();
        
        if ($reservation->table) {
            $reservation->table->status = 'available';
            $reservation->table->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibatalkan'
        ]);
    }
}
