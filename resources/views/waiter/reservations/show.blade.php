@extends('layouts.waiter')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Reservasi #{{ $reservation->id }}</h1>
            <p class="text-gray-600">{{ $reservation->customer_name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('waiter.reservations.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reservation Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Reservasi</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Reservasi:</span>
                    <span class="font-medium">#{{ $reservation->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
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
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu:</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jumlah Tamu:</span>
                    <span class="font-medium">{{ $reservation->guest_count }} orang</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Meja:</span>
                    <span class="font-medium">
                        @if($reservation->table)
                            Meja {{ $reservation->table->table_number }} ({{ $reservation->table->capacity }} kursi)
                        @else
                            <span class="text-yellow-600">Belum di-assign</span>
                        @endif
                    </span>
                </div>
                @if($reservation->special_requests)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Permintaan Khusus:</span>
                        <span class="font-medium">{{ $reservation->special_requests }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibuat:</span>
                    <span class="font-medium">{{ $reservation->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 space-y-2">
                @if($reservation->status === 'pending')
                    <button onclick="updateReservationStatus('confirmed')" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Konfirmasi Reservasi
                    </button>
                @endif
                
                @if($reservation->status === 'confirmed')
                    <button onclick="checkInReservation()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Check-in Customer
                    </button>
                @endif
                
                @if(!$reservation->table && in_array($reservation->status, ['pending', 'confirmed']))
                    <button onclick="assignTable()" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                        Assign Meja
                    </button>
                @endif
                
                @if(in_array($reservation->status, ['pending', 'confirmed']))
                    <button onclick="cancelReservation()" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Batalkan Reservasi
                    </button>
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Customer</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama:</span>
                    <span class="font-medium">{{ $reservation->customer_name }}</span>
                </div>
                @if($reservation->customer_phone)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Telepon:</span>
                        <span class="font-medium">{{ $reservation->customer_phone }}</span>
                    </div>
                @endif
                @if($reservation->customer_email)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $reservation->customer_email }}</span>
                    </div>
                @endif
                @if($reservation->user)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member:</span>
                        <span class="font-medium text-green-600">Ya ({{ $reservation->user->email }})</span>
                    </div>
                @else
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member:</span>
                        <span class="font-medium text-gray-500">Bukan member</span>
                    </div>
                @endif
            </div>

            <!-- Contact Actions -->
            <div class="mt-6 space-y-2">
                @if($reservation->customer_phone)
                    <a href="tel:{{ $reservation->customer_phone }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center block">
                        <i class="fas fa-phone mr-2"></i>Telepon Customer
                    </a>
                @endif
                @if($reservation->customer_email)
                    <a href="mailto:{{ $reservation->customer_email }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center block">
                        <i class="fas fa-envelope mr-2"></i>Email Customer
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Order History (if exists) -->
    @if($reservation->orders && $reservation->orders->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pesanan Terkait</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservation->orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                                 @switch($order->status)
                                                     @case('pending') bg-yellow-100 text-yellow-800 @break
                                                     @case('preparing') bg-blue-100 text-blue-800 @break
                                                     @case('ready') bg-green-100 text-green-800 @break
                                                     @case('served') bg-gray-100 text-gray-800 @break
                                                     @default bg-gray-100 text-gray-800
                                                 @endswitch">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('waiter.orders.show', $order) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
    // Update reservation status
    function updateReservationStatus(status) {
        if (!confirm('Yakin ingin mengubah status reservasi?')) {
            return;
        }

        fetch(`{{ route('waiter.reservations.updateStatus', $reservation) }}`, {
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
    function checkInReservation() {
        if (!confirm('Konfirmasi check-in untuk reservasi ini?')) {
            return;
        }

        fetch(`{{ route('waiter.reservations.checkIn', $reservation) }}`, {
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
    function cancelReservation() {
        if (!confirm('Yakin ingin membatalkan reservasi ini?')) {
            return;
        }

        fetch(`{{ route('waiter.reservations.cancel', $reservation) }}`, {
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
    function assignTable() {
        document.getElementById('assignTableModal').classList.remove('hidden');
        document.getElementById('assignTableModal').classList.add('flex');
    }

    function closeAssignTableModal() {
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

        fetch(`{{ route('waiter.reservations.assignTable', $reservation) }}`, {
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
