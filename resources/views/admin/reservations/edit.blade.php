@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Reservasi</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.reservations.show', $reservation) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Detail
            </a>
            <a href="{{ route('admin.reservations.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Pelanggan</h3>
                    
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan *</label>
                        <input type="text" 
                               id="customer_name" 
                               name="customer_name" 
                               value="{{ old('customer_name', $reservation->customer_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                        <input type="tel" 
                               id="customer_phone" 
                               name="customer_phone" 
                               value="{{ old('customer_phone', $reservation->customer_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('customer_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               id="customer_email" 
                               name="customer_email" 
                               value="{{ old('customer_email', $reservation->customer_email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('customer_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reservation Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Detail Reservasi</h3>
                    
                    <div>
                        <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Reservasi *</label>
                        <input type="date" 
                               id="reservation_date" 
                               name="reservation_date" 
                               value="{{ old('reservation_date', $reservation->reservation_time ? $reservation->reservation_time->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('reservation_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reservation_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Reservasi *</label>
                        <select id="reservation_time" 
                                name="reservation_time" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Pilih waktu reservasi</option>
                            @php
                                $times = [];
                                // Operating hours: 09:00 - 22:00 (every 30 minutes)
                                for ($hour = 9; $hour <= 21; $hour++) {
                                    for ($minute = 0; $minute < 60; $minute += 30) {
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                        $times[] = $time;
                                    }
                                }
                                // Add 22:00 as last slot
                                $times[] = '22:00';
                                $currentTime = old('reservation_time', $reservation->reservation_time ? $reservation->reservation_time->format('H:i') : '');
                            @endphp
                            @foreach($times as $time)
                                <option value="{{ $time }}" {{ $currentTime == $time ? 'selected' : '' }}>
                                    {{ $time }}
                                </option>
                            @endforeach
                        </select>
                        @error('reservation_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Jam operasional: 09:00 - 22:00</p>
                    </div>

                    <div>
                        <label for="party_size" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tamu *</label>
                        <select id="party_size" 
                                name="party_size" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Pilih jumlah tamu</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('party_size', $reservation->party_size) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'orang' : 'orang' }}
                                </option>
                            @endfor
                        </select>
                        @error('party_size')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="table_id" class="block text-sm font-medium text-gray-700 mb-2">Meja</label>
                        <select id="table_id" 
                                name="table_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih otomatis berdasarkan kapasitas</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}" 
                                        data-capacity="{{ $table->capacity }}"
                                        {{ old('table_id', $reservation->table_id) == $table->id ? 'selected' : '' }}>
                                    Meja {{ $table->table_number }} ({{ $table->capacity }} orang) - {{ ucfirst($table->status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('table_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="seated" {{ old('status', $reservation->status) == 'seated' ? 'selected' : '' }}>Sedang Makan</option>
                            <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="no_show" {{ old('status', $reservation->status) == 'no_show' ? 'selected' : '' }}>Tidak Hadir</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Special Requests -->
            <div class="mt-6">
                <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">Permintaan Khusus</label>
                <textarea id="special_requests" 
                          name="special_requests" 
                          rows="3"
                          placeholder="Contoh: Perayaan ulang tahun, alergi makanan, posisi meja tertentu..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('special_requests', $reservation->special_requests) }}</textarea>
                @error('special_requests')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex justify-between items-center mt-6">
                <!-- Delete Button (Left) -->
                <div>
                    @if(!in_array($reservation->status, ['completed', 'cancelled', 'no_show']))
                        <button type="button" 
                                onclick="confirmDelete()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Reservasi
                        </button>
                        <p class="text-xs text-gray-500 mt-1">*Hanya reservasi yang belum selesai yang dapat dihapus</p>
                    @else
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Reservasi dengan status {{ $reservation->status }} tidak dapat dihapus
                        </div>
                    @endif
                </div>

                <!-- Action Buttons (Right) -->
                <div class="flex space-x-4">
                    <a href="{{ route('admin.reservations.show', $reservation) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Perbarui Reservasi
                    </button>
                </div>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="deleteForm" action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
// Delete confirmation function
function confirmDelete() {
    const reservationName = '{{ $reservation->customer_name }}';
    const reservationDate = '{{ $reservation->reservation_time ? $reservation->reservation_time->format("d/m/Y H:i") : "-" }}';
    
    const confirmMessage = `Apakah Anda yakin ingin menghapus reservasi ini?\n\n` +
                          `Pelanggan: ${reservationName}\n` +
                          `Tanggal: ${reservationDate}\n\n` +
                          `⚠️ PERINGATAN: Tindakan ini tidak dapat dibatalkan!\n` +
                          `Semua data reservasi dan riwayatnya akan dihapus permanen.`;
    
    if (confirm(confirmMessage)) {
        // Show loading state
        const deleteBtn = event.target;
        const originalText = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
        deleteBtn.disabled = true;
        
        document.getElementById('deleteForm').submit();
    }
}
// Filter tables based on party size
document.getElementById('party_size').addEventListener('change', function() {
    const partySize = parseInt(this.value);
    const tableSelect = document.getElementById('table_id');
    const options = tableSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        const capacity = parseInt(option.dataset.capacity);
        if (capacity >= partySize) {
            option.style.display = 'block';
            option.disabled = false;
        } else {
            option.style.display = 'none';
            option.disabled = true;
        }
    });
    
    // Reset table selection if current selection is not suitable
    const selectedOption = tableSelect.options[tableSelect.selectedIndex];
    if (selectedOption && selectedOption.dataset.capacity && parseInt(selectedOption.dataset.capacity) < partySize) {
        tableSelect.value = '';
    }
});

// Trigger party size change on page load to filter initial options
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('party_size').dispatchEvent(new Event('change'));
});
</script>
@endsection
