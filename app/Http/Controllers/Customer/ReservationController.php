<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return view('customer.reservation');
    }

    public function preview(Request $request)
    {
        // Debug: tampilkan data yang diterima
        dd('Form submitted!', $request->all());
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'guest_count' => 'required|integer|min:1|max:20',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|string',
                'allergies' => 'nullable|string|max:500',
                'occasion' => 'nullable|string|max:255',
                'table_preference' => 'nullable|string|in:indoor,outdoor,window,private'
            ]);

            $availableTables = Table::where('capacity', '>=', $validated['guest_count'])
                                  ->where('is_available', true)
                                  ->get();

            return view('customer.reservation-preview', compact('validated', 'availableTables'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Reservation preview error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses reservasi. Silakan coba lagi.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'table_id' => 'required|exists:tables,id',
            'table_preference' => 'nullable|string',
            'occasion' => 'nullable|string',
            'allergies' => 'nullable|string|max:500',
            'accessibility_needs' => 'nullable|string|max:500',
            'special_requests' => 'nullable|string|max:1000'
        ]);

        // Update table status to reserved
        $table = Table::findOrFail($request->table_id);
        $table->update(['status' => 'reserved']);

        // In real implementation, save to reservations table
        // For now, just return success message
        return redirect()->route('customer.reservation')->with('success', 'Reservasi Anda berhasil dikirim! Meja nomor ' . $table->table_number . ' telah direservasi untuk Anda.');
    }
}
