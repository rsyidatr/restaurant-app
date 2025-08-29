@extends('layouts.waiter')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Reservasi</h1>
        <div class="flex space-x-2">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Check-in</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>Tidak Datang</option>
            </select>
            <input type="date" id="dateFilter" value="{{ request('date') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-md">
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" 
                       id="searchReservation" 
                       placeholder="Cari nama customer..."
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select id="timeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Waktu</option>
                    <option value="morning" {{ request('time') == 'morning' ? 'selected' : '' }}>Pagi (06:00-12:00)</option>
                    <option value="afternoon" {{ request('time') == 'afternoon' ? 'selected' : '' }}>Siang (12:00-18:00)</option>
                    <option value="evening" {{ request('time') == 'evening' ? 'selected' : '' }}>Malam (18:00-24:00)</option>
                </select>
            </div>
            <div>
                <button onclick="refreshReservations()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Reservations List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Reservasi</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50" id="reservation-row-{{ $reservation->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $reservation->id }}</div>
                            <div class="text-sm text-gray-500">{{ $reservation->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->customer_name }}</div>
                            @if($reservation->customer_phone)
                                <div class="text-sm text-gray-500">{{ $reservation->customer_phone }}</div>
                            @endif
                            @if($reservation->customer_email)
                                <div class="text-sm text-gray-500">{{ $reservation->customer_email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reservation->table)
                                <div class="text-sm font-medium text-gray-900">
                                    Meja {{ $reservation->table->table_number }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $reservation->table->capacity }} kursi
                                </div>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    Belum Assign
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->guest_count }} orang</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                         @switch($reservation->status)
                                             @case('pending') bg-yellow-100 text-yellow-800 @break
                                             @case('confirmed') bg-blue-100 text-blue-800 @break
                                             @case('checked_in') bg-green-100 text-green-800 @break
                                             @case('completed') bg-gray-100 text-gray-800 @break
                                             @case('cancelled') bg-red-100 text-red-800 @break
                                             @case('no_show') bg-red-100 text-red-800 @break
                                             @default bg-gray-100 text-gray-800
                                         @endswitch">
                                @switch($reservation->status)
                                    @case('pending') Menunggu @break
                                    @case('confirmed') Dikonfirmasi @break
                                    @case('checked_in') Check-in @break
                                    @case('completed') Selesai @break
                                    @case('cancelled') Dibatalkan @break
                                    @case('no_show') Tidak Datang @break
                                    @default {{ ucfirst($reservation->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('waiter.reservations.show', $reservation) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($reservation->status === 'pending')
                                    <button onclick="updateReservationStatus({{ $reservation->id }}, 'confirmed')" 
                                            class="text-green-600 hover:text-green-900"
                                            title="Konfirmasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                
                                @if($reservation->status === 'confirmed')
                                    <button onclick="checkInReservation({{ $reservation->id }})" 
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Check-in">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                @endif
                                
                                @if(!$reservation->table && in_array($reservation->status, ['pending', 'confirmed']))
                                    <button onclick="assignTable({{ $reservation->id }})" 
                                            class="text-purple-600 hover:text-purple-900"
                                            title="Assign Meja">
                                        <i class="fas fa-table"></i>
                                    </button>
                                @endif
                                
                                @if(in_array($reservation->status, ['pending', 'confirmed']))
                                    <button onclick="cancelReservation({{ $reservation->id }})" 
                                            class="text-red-600 hover:text-red-900"
                                            title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                                <p>Tidak ada reservasi yang ditemukan.</p>
                            </div>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($reservations->hasPages())
        <div class="mt-8">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

<!-- Assign Table Modal -->
<div id="assignTableModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Meja</h3>
        <form id="assignTableForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Meja:</label>
                <select id="tableSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Pilih meja...</option>
                    @foreach($availableTables ?? [] as $table)
                        <option value="{{ $table->id }}">Meja {{ $table->table_number }} ({{ $table->capacity }} kursi)</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="button" onclick="closeAssignTableModal()" 
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Assign
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentReservationId = null;

    // Auto refresh every 30 seconds
    setInterval(function() {
        if (!document.hidden && !document.querySelector('.modal:not(.hidden)')) {
            location.reload();
        }
    }, 30000);

    // Filter functions
    function refreshReservations() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchReservation').value;
        const date = document.getElementById('dateFilter').value;
        const time = document.getElementById('timeFilter').value;
        
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (search) params.append('search', search);
        if (date) params.append('date', date);
        if (time) params.append('time', time);
        
        const url = `{{ route('waiter.reservations.index') }}?${params.toString()}`;
        window.location.href = url;
    }

    // Add event listeners for real-time filtering
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('statusFilter').addEventListener('change', refreshReservations);
        document.getElementById('dateFilter').addEventListener('change', refreshReservations);
        document.getElementById('timeFilter').addEventListener('change', refreshReservations);
        
        // Add search functionality with debounce
        let searchTimeout;
        document.getElementById('searchReservation').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(refreshReservations, 500);
        });
    });

    // Update reservation status
    function updateReservationStatus(reservationId, status) {
        if (!confirm('Yakin ingin mengubah status reservasi?')) {
            return;
        }

        fetch(`/waiter/reservations/${reservationId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showError('Gagal mengubah status reservasi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan');
        });
    }

    // Check-in reservation
    function checkInReservation(reservationId) {
        if (!confirm('Konfirmasi check-in untuk reservasi ini?')) {
            return;
        }

        fetch(`/waiter/reservations/${reservationId}/check-in`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showError(data.message || 'Gagal check-in reservasi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan');
        });
    }

    // Cancel reservation
    function cancelReservation(reservationId) {
        if (!confirm('Yakin ingin membatalkan reservasi ini?')) {
            return;
        }

        fetch(`/waiter/reservations/${reservationId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showError(data.message || 'Gagal membatalkan reservasi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan');
        });
    }

    // Assign table
    function assignTable(reservationId) {
        currentReservationId = reservationId;
        document.getElementById('assignTableModal').classList.remove('hidden');
        document.getElementById('assignTableModal').classList.add('flex');
    }

    function closeAssignTableModal() {
        currentReservationId = null;
        document.getElementById('assignTableModal').classList.add('hidden');
        document.getElementById('assignTableModal').classList.remove('flex');
        document.getElementById('tableSelect').value = '';
    }

    // Handle assign table form
    document.getElementById('assignTableForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const tableId = document.getElementById('tableSelect').value;
        if (!tableId) {
            showWarning('Pilih meja terlebih dahulu');
            return;
        }

        fetch(`/waiter/reservations/${currentReservationId}/assign-table`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ table_id: tableId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeAssignTableModal();
                location.reload();
            } else {
                showError(data.message || 'Gagal assign meja');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan');
        });
    });
</script>
@endpush
@endsection
