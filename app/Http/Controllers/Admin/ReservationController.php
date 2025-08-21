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
        
        // Filter by table
        if ($request->has('table_id') && $request->table_id != '') {
            $query->where('table_id', $request->table_id);
        }
        
        // Search by customer name
        if ($request->has('search') && $request->search != '') {
            $query->where('customer_name', 'ILIKE', '%' . $request->search . '%');
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
        $tables = Table::bookable()->where('status', 'available')->orderBy('table_number')->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        
        return view('admin.reservations.create', compact('tables', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'party_size' => 'required|integer|min:1|max:12',
            'table_id' => 'nullable|exists:tables,id',
            'special_requests' => 'nullable|string'
        ]);

        // Combine date and time
        $reservationDateTime = Carbon::parse($request->reservation_date . ' ' . $request->reservation_time);

        // Check if reservation time is in the future
        if ($reservationDateTime->isPast()) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Waktu reservasi harus di masa depan.');
        }

        // Check if table is available at the requested time (if table is specified)
        if ($request->table_id) {
            $conflictingReservation = Reservation::where('table_id', $request->table_id)
                                                ->where('status', 'confirmed')
                                                ->whereBetween('reservation_time', [
                                                    $reservationDateTime->copy()->subHours(2),
                                                    $reservationDateTime->copy()->addHours(2)
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
        }

        Reservation::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'reservation_time' => $reservationDateTime,
            'party_size' => $request->party_size,
            'table_id' => $request->table_id,
            'special_requests' => $request->special_requests,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.reservations.index')
                        ->with('success', 'Reservasi berhasil dibuat.');
    }

    public function edit(Reservation $reservation)
    {
        $tables = Table::bookable()
                      ->where(function($query) use ($reservation) {
                          $query->where('status', 'available')
                                ->orWhere('id', $reservation->table_id);
                      })
                      ->orderBy('table_number')
                      ->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        
        return view('admin.reservations.edit', compact('reservation', 'tables', 'customers'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'party_size' => 'required|integer|min:1|max:12',
            'table_id' => 'nullable|exists:tables,id',
            'special_requests' => 'nullable|string'
        ]);

        // Combine date and time
        $reservationDateTime = Carbon::parse($request->reservation_date . ' ' . $request->reservation_time);

        // Check if reservation time is in the future (only for pending reservations)
        if ($reservation->status === 'pending' && $reservationDateTime->isPast()) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Waktu reservasi harus di masa depan.');
        }

        // Check if table is available at the requested time (excluding current reservation and if table is specified)
        if ($request->table_id) {
            $conflictingReservation = Reservation::where('table_id', $request->table_id)
                                                ->where('id', '!=', $reservation->id)
                                                ->where('status', 'confirmed')
                                                ->whereBetween('reservation_time', [
                                                    $reservationDateTime->copy()->subHours(2),
                                                    $reservationDateTime->copy()->addHours(2)
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
        }

        $reservation->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'reservation_time' => $reservationDateTime,
            'party_size' => $request->party_size,
            'table_id' => $request->table_id,
            'special_requests' => $request->special_requests
        ]);

        return redirect()->route('admin.reservations.index')
                        ->with('success', 'Reservasi berhasil diperbarui.');
    }
    
    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'table']);
        
        // Load orders safely - only if reservation_id column exists and has data
        try {
            $reservation->load(['orders.orderItems.menuItem']);
        } catch (\Exception $e) {
            // If there's an error loading orders, just continue without them
            // This handles cases where reservation_id column doesn't exist yet
        }
        
        return view('admin.reservations.show', compact('reservation'));
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
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,seated,completed,cancelled,no_show'
            ]);
            
            $oldStatus = $reservation->status;
            $newStatus = $validated['status'];
            
            $reservation->update(['status' => $newStatus]);
            
            // Update table status based on reservation status
            if ($reservation->table) {
                if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
                    $reservation->table->update(['status' => 'reserved']);
                } elseif ($newStatus === 'seated' && $oldStatus === 'confirmed') {
                    $reservation->table->update(['status' => 'occupied']);
                } elseif ($newStatus === 'completed' && in_array($oldStatus, ['seated', 'confirmed'])) {
                    $reservation->table->update(['status' => 'available']);
                } elseif ($newStatus === 'cancelled' && $oldStatus === 'confirmed') {
                    $reservation->table->update(['status' => 'available']);
                }
            }
            
            // Generate appropriate success message
            $messages = [
                'confirmed' => 'Reservasi berhasil dikonfirmasi!',
                'seated' => 'Customer berhasil check-in!',
                'completed' => 'Reservasi berhasil diselesaikan!',
                'cancelled' => 'Reservasi berhasil dibatalkan!',
                'no_show' => 'Status reservasi diubah menjadi tidak hadir!'
            ];
            
            $message = $messages[$newStatus] ?? 'Status reservasi berhasil diperbarui!';
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
