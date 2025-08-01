@extends('layouts.customer')

@section('title', 'Checkout - Restoran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-2">Silakan periksa pesanan Anda dan lengkapi informasi checkout</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 p-4 border rounded-lg">
                            <img src="{{ $item->image }}" alt="{{ $item->menu_name }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $item->menu_name }}</h3>
                                <p class="text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                @if($item->notes)
                                <p class="text-sm text-gray-500 mt-1">Catatan: {{ $item->notes }}</p>
                                @endif
                            </div>
                            
                            <div class="text-center">
                                <p class="text-gray-600">Qty: {{ $item->quantity }}</p>
                                <p class="font-semibold text-gray-900">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex justify-between items-center text-xl font-bold text-gray-900">
                            <span>Total:</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Checkout</h2>
                    
                    <form id="checkoutForm" class="space-y-4">
                        @csrf
                        
                        <!-- Pilih Meja -->
                        <div>
                            <label for="table_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Meja *
                            </label>
                            <select name="table_id" id="table_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Meja --</option>
                                @foreach($availableTables as $table)
                                <option value="{{ $table->id }}">
                                    Meja {{ $table->table_number }} (Kapasitas: {{ $table->capacity }} orang)
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Pelanggan -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pelanggan *
                            </label>
                            <input type="text" name="customer_name" id="customer_name" required
                                   value="{{ auth()->user()->name ?? '' }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon *
                            </label>
                            <input type="tel" name="customer_phone" id="customer_phone" required
                                   value="{{ auth()->user()->phone ?? '' }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran *
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="cash" required
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    <span>Cash</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="transfer" required
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    <span>Transfer Bank</span>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Catatan tambahan untuk pesanan..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold transition-colors">
                            <span id="submitText">Konfirmasi Pesanan</span>
                            <span id="loadingText" class="hidden">Memproses...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Memproses pesanan Anda...</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');
    const loadingModal = document.getElementById('loadingModal');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    loadingText.classList.remove('hidden');
    loadingModal.classList.remove('hidden');
    loadingModal.classList.add('flex');
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('{{ route("checkout.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            throw new Error(data.error || 'Terjadi kesalahan');
        }
        
    } catch (error) {
        // Hide loading
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        loadingText.classList.add('hidden');
        loadingModal.classList.add('hidden');
        loadingModal.classList.remove('flex');
        
        alert('Error: ' + error.message);
    }
});
</script>
@endpush
