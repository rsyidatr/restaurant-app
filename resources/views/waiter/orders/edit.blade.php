@extends('layouts.waiter')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Pesanan</h1>
            <p class="text-gray-600">Order #{{ $order->order_number }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('waiter.orders.show', $order) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Edit -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Informasi Pesanan</h2>
                
                <form action="{{ route('waiter.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Pesanan
                            </label>
                            <input type="text" 
                                   value="{{ $order->order_number }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                                   disabled>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <input type="text" 
                                   value="{{ ucfirst($order->status) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                                   disabled>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pelanggan
                            </label>
                            <input type="text" 
                                   value="{{ $order->customer_name ?? ($order->user ? $order->user->name : 'Guest') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                                   disabled>
                        </div>
                        
                        <div>
                            <label for="table_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Meja <span class="text-red-500">*</span>
                            </label>
                            <select name="table_id" id="table_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">Pilih Meja</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" 
                                            {{ $order->table_id == $table->id ? 'selected' : '' }}>
                                        Meja {{ $table->table_number }} 
                                        @if($table->id !== $order->table_id)
                                            ({{ ucfirst($table->status) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('table_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Pesanan
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Tambahkan catatan untuk pesanan ini...">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Instruksi Khusus untuk Dapur
                        </label>
                        <textarea name="special_instructions" id="special_instructions" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Instruksi khusus untuk dapur (contoh: tanpa pedas, extra sauce, dll)">{{ old('special_instructions', $order->special_instructions) }}</textarea>
                        @error('special_instructions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('waiter.orders.show', $order) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md"
                                onclick="return confirmEdit()">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Details -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Detail Pesanan</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipe Pesanan:</span>
                        <span class="font-medium">{{ ucfirst($order->order_type) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total:</span>
                        <span class="font-bold text-lg">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="font-semibold mb-3">Item Pesanan</h3>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                        <div class="flex justify-between text-sm">
                            <div>
                                <div class="font-medium">{{ $item->menuItem->name }}</div>
                                <div class="text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="font-medium">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($order->notes)
                    <hr class="my-4">
                    <h3 class="font-semibold mb-2">Catatan Saat Ini</h3>
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
                @endif

                @if($order->special_instructions)
                    <hr class="my-4">
                    <h3 class="font-semibold mb-2">Instruksi Khusus Saat Ini</h3>
                    <p class="text-sm text-gray-600 bg-yellow-50 p-3 rounded">{{ $order->special_instructions }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Edit confirmation functionality
function confirmEdit() {
    const confirmId = showWarning('Yakin ingin menyimpan perubahan pesanan ini?', false);
    
    // Create custom confirmation dialog
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    // Add confirm/cancel buttons
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedEdit('${confirmId}')" 
                class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
            Ya, Simpan
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
    
    return false; // Prevent form submission
}

function proceedEdit(confirmId) {
    // Hide confirmation dialog
    notificationManager.hide(confirmId);
    
    // Show processing notification
    const processingId = showInfo('Menyimpan perubahan...', false);
    
    // Submit the form
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        // Hide processing notification
        notificationManager.hide(processingId);
        
        if (response.redirected) {
            showSuccess('Perubahan berhasil disimpan!');
            setTimeout(() => {
                window.location.href = response.url;
            }, 1000);
        } else {
            return response.text().then(html => {
                // If there are validation errors, reload the page with errors
                document.open();
                document.write(html);
                document.close();
            });
        }
    })
    .catch(error => {
        // Hide processing notification
        notificationManager.hide(processingId);
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menyimpan perubahan');
    });
}

// Auto-save functionality bisa ditambahkan di sini jika diperlukan
document.getElementById('table_id').addEventListener('change', function() {
    if (this.value) {
        // Bisa tambahkan validasi atau notifikasi jika diperlukan
        console.log('Table changed to:', this.value);
        showInfo('Meja berubah ke: ' + this.options[this.selectedIndex].text);
    }
});

// Show notification when notes or special instructions are changed
document.getElementById('notes').addEventListener('change', function() {
    if (this.value.trim()) {
        showInfo('Catatan pesanan diubah');
    }
});

document.getElementById('special_instructions').addEventListener('change', function() {
    if (this.value.trim()) {
        showInfo('Instruksi khusus diubah');
    }
});
</script>
@endsection
