<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'table']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('reservation_time', $request->date);
        } else {
            // Default to today and future reservations
            $query->whereDate('reservation_time', '>=', today());
        }
        
        // Search by customer name
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function($userQuery) use ($request) {
                $userQuery->where('name', 'ILIKE', '%' . $request->search . '%');
            });
        }
        
        $reservations = $query->orderBy('reservation_time', 'asc')->paginate(15);
        
        // Statistics
        $stats = [
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'today_reservations' => Reservation::whereDate('reservation_time', today())->count(),
        ];

        // Get all tables for filter dropdown
        $tables = Table::orderBy('table_number')->get();
        
        return view('admin.reservations.index', compact('reservations', 'stats', 'tables'));
    }

    public function create()
    {
        $tables = Table::where('status', 'available')->orderBy('table_number')->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        
        return view('admin.reservations.create', compact('tables', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_time' => 'required|date|after:now',
            'party_size' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Check if table is available at the requested time
        $conflictingReservation = Reservation::where('table_id', $request->table_id)
                                            ->where('status', 'confirmed')
                                            ->whereBetween('reservation_time', [
                                                Carbon::parse($request->reservation_time)->subHours(2),
                                                Carbon::parse($request->reservation_time)->addHours(2)
                                            ])
                                            ->exists();

        if ($conflictingReservation) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Meja sudah ada reservasi pada waktu tersebut. Pilih waktu lain.');
        }

        // Check table capacity
        $table = Table::find($request->table_id);
        if ($table->capacity < $request->party_size) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Kapasitas meja tidak mencukupi untuk jumlah tamu.');
        }

        Reservation::create($request->all());

        return redirect()->route('admin.reservations.index')
                        ->with('success', 'Reservasi berhasil dibuat.');
    }

    public function edit(Reservation $reservation)
    {
        $tables = Table::where('status', 'available')
                      ->orWhere('id', $reservation->table_id)
                      ->orderBy('table_number')
                      ->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        
        return view('admin.reservations.edit', compact('reservation', 'tables', 'customers'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_time' => 'required|date|after:now',
            'party_size' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Check if table is available at the requested time (excluding current reservation)
        $conflictingReservation = Reservation::where('table_id', $request->table_id)
                                            ->where('id', '!=', $reservation->id)
                                            ->where('status', 'confirmed')
                                            ->whereBetween('reservation_time', [
                                                Carbon::parse($request->reservation_time)->subHours(2),
                                                Carbon::parse($request->reservation_time)->addHours(2)
                                            ])
                                            ->exists();

        if ($conflictingReservation) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Meja sudah ada reservasi pada waktu tersebut. Pilih waktu lain.');
        }

        // Check table capacity
        $table = Table::find($request->table_id);
        if ($table->capacity < $request->party_size) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Kapasitas meja tidak mencukupi untuk jumlah tamu.');
        }

        $reservation->update($request->all());

        return redirect()->route('admin.reservations.index')
                        ->with('success', 'Reservasi berhasil diperbarui.');
    }
    
    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'table', 'orders.orderItems.menuItem']);
        
        return view('admin.reservations.show', compact('reservation'));
    }
    
    public function confirm(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmed']);
        
        // Update table status to reserved
        $reservation->table->update(['status' => 'reserved']);

        return redirect()->back()
                        ->with('success', 'Reservasi berhasil dikonfirmasi.');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);
        
        // If table was reserved, make it available
        if ($reservation->table->status === 'reserved') {
            $reservation->table->update(['status' => 'available']);
        }

        return redirect()->back()
                        ->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function checkIn(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed') {
            return redirect()->back()
                            ->with('error', 'Hanya reservasi yang sudah dikonfirmasi yang bisa check-in.');
        }

        // Update table status to occupied
        $reservation->table->update(['status' => 'occupied']);

        return redirect()->back()
                        ->with('success', 'Customer berhasil check-in. Meja sekarang ditempati.');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->status === 'confirmed' && $reservation->reservation_time <= now()->addHours(2)) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus reservasi yang akan dimulai dalam 2 jam.');
        }

        // Release table if reservation was confirmed
        if ($reservation->table && $reservation->status === 'confirmed') {
            $reservation->table->update(['status' => 'available']);
        }

        $reservation->delete();

        return redirect()->route('admin.reservations.index')
                        ->with('success', 'Reservasi berhasil dihapus.');
    }
    
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);
        
        $oldStatus = $reservation->status;
        $reservation->update($validated);
        
        // Update table status based on reservation status
        if ($reservation->table) {
            if ($validated['status'] === 'confirmed' && $oldStatus !== 'confirmed') {
                $reservation->table->update(['status' => 'reserved']);
            } elseif ($validated['status'] === 'cancelled' && $oldStatus === 'confirmed') {
                $reservation->table->update(['status' => 'available']);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Status reservasi berhasil diperbarui',
            'status' => $reservation->status
        ]);
    }
    
    public function assignTable(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id'
        ]);
        
        $table = Table::find($validated['table_id']);
        
        // Check if table is available
        if ($table->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Meja yang dipilih tidak tersedia'
            ]);
        }
        
        // Check table capacity
        if ($table->capacity < $reservation->party_size) {
            return response()->json([
                'success' => false,
                'message' => 'Kapasitas meja tidak mencukupi untuk jumlah tamu'
            ]);
        }
        
        // Release old table if any
        if ($reservation->table && $reservation->status === 'confirmed') {
            $reservation->table->update(['status' => 'available']);
        }
        
        $reservation->update(['table_id' => $validated['table_id']]);
        
        // Update new table status if reservation is confirmed
        if ($reservation->status === 'confirmed') {
            $table->update(['status' => 'reserved']);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditetapkan'
        ]);
    }
}
