<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class ReservationController extends Controller
{
    public function index()
    {
        return view('customer.reservations.create');
    }

    public function preview(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'guest_count' => 'required|integer|in:2,4,6,8,10,15,20,30',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|string',
                'allergies' => 'nullable|string|max:500',
                'occasion' => 'nullable|string|max:255',
                'table_preference' => 'nullable|string|in:smoking,non_smoking'
            ]);

            $availableTables = Table::bookable()
                                  ->where('capacity', '>=', $validated['guest_count'])
                                  ->where('status', 'available')
                                  ->get();

            // Map field names to match what the view expects
            $reservationData = $validated;
            $reservationData['reservation_date'] = $validated['date'];
            $reservationData['reservation_time'] = $validated['time'];

            return view('customer.reservations.preview', compact('reservationData', 'availableTables'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Reservation preview error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses reservasi. Silakan coba lagi.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'guest_count' => 'required|integer|in:2,4,6,8,10,15,20,30',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'table_id' => 'required|exists:tables,id',
            'table_preference' => 'nullable|string',
            'occasion' => 'nullable|string',
            'allergies' => 'nullable|string|max:500',
            'accessibility_needs' => 'nullable|string|max:500',
            'special_requests' => 'nullable|string|max:1000'
        ]);

        try {
            // Combine date and time for reservation_time
            $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);

            // Prepare special requests data
            $specialRequests = [];
            if (!empty($validated['table_preference'])) {
                $specialRequests[] = "Preferensi meja: " . $validated['table_preference'];
            }
            if (!empty($validated['occasion'])) {
                $specialRequests[] = "Acara: " . $validated['occasion'];
            }
            if (!empty($validated['allergies'])) {
                $specialRequests[] = "Alergi: " . $validated['allergies'];
            }
            if (!empty($validated['accessibility_needs'])) {
                $specialRequests[] = "Aksesibilitas: " . $validated['accessibility_needs'];
            }
            if (!empty($validated['special_requests'])) {
                $specialRequests[] = "Permintaan khusus: " . $validated['special_requests'];
            }

            // Create the reservation record
            $reservation = Reservation::create([
                'user_id' => auth()->id(), // null if guest user
                'table_id' => $validated['table_id'],
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'reservation_time' => $reservationDateTime,
                'party_size' => $validated['guest_count'],
                'status' => 'pending',
                'special_requests' => !empty($specialRequests) ? implode('; ', $specialRequests) : null
            ]);

            // Update table status to reserved
            $table = Table::findOrFail($validated['table_id']);
            $table->update(['status' => 'reserved']);

            return redirect()->route('customer.reservation')->with('success', 
                'Reservasi Anda berhasil dikirim! Nomor reservasi: #' . $reservation->id . '. Meja nomor ' . $table->table_number . ' telah direservasi untuk Anda. Tim kami akan menghubungi Anda dalam 24 jam untuk konfirmasi.');

        } catch (Exception $e) {
            Log::error('Reservation store error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan reservasi. Silakan coba lagi.');
        }
    }
}
