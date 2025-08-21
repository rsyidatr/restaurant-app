@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Reservasi</h1>
        <a href="{{ route('admin.reservations.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.reservations.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Pelanggan</h3>
                    
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan *</label>
                        <input type="text" 
                               id="customer_name" 
                               name="customer_name" 
                               value="{{ old('customer_name') }}"
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
                               value="{{ old('customer_phone') }}"
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
                               value="{{ old('customer_email') }}"
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
                               value="{{ old('reservation_date') }}"
                               min="{{ date('Y-m-d') }}"
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
                            @endphp
                            @foreach($times as $time)
                                <option value="{{ $time }}" {{ old('reservation_time') == $time ? 'selected' : '' }}>
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
                                <option value="{{ $i }}" {{ old('party_size') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'orang' : 'orang' }}
                                </option>
                            @endfor
                        </select>
                        @error('party_size')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="table_id" class="block text-sm font-medium text-gray-700 mb-2">Meja (Opsional)</label>
                        <select id="table_id" 
                                name="table_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih otomatis berdasarkan kapasitas</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}" 
                                        data-capacity="{{ $table->capacity }}"
                                        {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                    Meja {{ $table->table_number }} ({{ $table->capacity }} orang) - {{ ucfirst($table->status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('table_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong untuk pemilihan otomatis berdasarkan kapasitas dan ketersediaan</p>
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.reservations.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan Reservasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

// Set minimum time based on current time if date is today
document.getElementById('reservation_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    const timeSelect = document.getElementById('reservation_time');
    
    if (selectedDate.toDateString() === today.toDateString()) {
        // If selected date is today, disable past times
        const currentTime = new Date();
        const currentHour = currentTime.getHours();
        const currentMinute = currentTime.getMinutes();
        const currentTimeInMinutes = currentHour * 60 + currentMinute;
        
        const options = timeSelect.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') return;
            
            const [hour, minute] = option.value.split(':').map(Number);
            const optionTimeInMinutes = hour * 60 + minute;
            
            // Disable if time is in the past (add 1 hour buffer)
            if (optionTimeInMinutes <= currentTimeInMinutes + 60) {
                option.disabled = true;
                option.style.color = '#9CA3AF';
            } else {
                option.disabled = false;
                option.style.color = '';
            }
        });
        
        // Reset selection if current selection is disabled
        const selectedOption = timeSelect.options[timeSelect.selectedIndex];
        if (selectedOption && selectedOption.disabled) {
            timeSelect.value = '';
        }
    } else {
        // If selected date is in the future, enable all times
        const options = timeSelect.querySelectorAll('option');
        options.forEach(option => {
            option.disabled = false;
            option.style.color = '';
        });
    }
});

// Trigger the date change event on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reservation_date').dispatchEvent(new Event('change'));
});
</script>
@endsection
