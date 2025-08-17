@extends('layouts.waiter')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('waiter.orders.index') }}" 
                   class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap dan kontrol pesanan</p>
                </div>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-receipt text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status & Progress -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Status & Progress Pesanan</h3>
                
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-200"></div>
                    
                    <!-- Order Created -->
                    <div class="relative flex items-center mb-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-green-500 text-white">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-gray-900">Pesanan Dibuat</div>
                            <div class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>

                    <!-- Order Confirmed -->
                    <div class="relative flex items-center mb-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                    {{ $order->confirmed_at ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium {{ $order->confirmed_at ? 'text-gray-900' : 'text-gray-500' }}">
                                Pesanan Dikonfirmasi
                            </div>
                            @if($order->confirmed_at)
                                <div class="text-sm text-gray-600">{{ $order->confirmed_at->format('d/m/Y H:i:s') }}</div>
                            @else
                                <div class="text-sm text-gray-500">Menunggu konfirmasi</div>
                            @endif
                        </div>
                    </div>

                    <!-- Cooking Started -->
                    <div class="relative flex items-center mb-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                    {{ in_array($order->status, ['preparing', 'ready', 'served']) ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <i class="fas fa-fire text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium {{ in_array($order->status, ['preparing', 'ready', 'served']) ? 'text-gray-900' : 'text-gray-500' }}">
                                Mulai Dimasak
                            </div>
                            @if($order->started_cooking_at)
                                <div class="text-sm text-gray-600">{{ $order->started_cooking_at->format('d/m/Y H:i:s') }}</div>
                            @elseif($order->status === 'pending')
                                <div class="text-sm text-gray-500">Menunggu dapur</div>
                            @endif
                        </div>
                    </div>

                    <!-- Ready to Serve -->
                    <div class="relative flex items-center mb-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                    {{ in_array($order->status, ['ready', 'served']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <i class="fas fa-bell text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium {{ in_array($order->status, ['ready', 'served']) ? 'text-gray-900' : 'text-gray-500' }}">
                                Siap Disajikan
                            </div>
                            @if($order->ready_at)
                                <div class="text-sm text-gray-600">{{ $order->ready_at->format('d/m/Y H:i:s') }}</div>
                            @elseif(in_array($order->status, ['pending', 'preparing']))
                                <div class="text-sm text-gray-500">Belum siap</div>
                            @endif
                        </div>
                    </div>

                    <!-- Served -->
                    <div class="relative flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                    {{ $order->status === 'served' ? 'bg-gray-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <i class="fas fa-utensils text-sm"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium {{ $order->status === 'served' ? 'text-gray-900' : 'text-gray-500' }}">
                                Disajikan ke Pelanggan
                            </div>
                            @if($order->served_at)
                                <div class="text-sm text-gray-600">{{ $order->served_at->format('d/m/Y H:i:s') }}</div>
                            @else
                                <div class="text-sm text-gray-500">Belum disajikan</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Pesanan</h3>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-start space-x-4 p-4 border rounded-lg">
                        @if($item->menuItem->image_url)
                            <img class="w-16 h-16 rounded-lg object-cover" 
                                 src="{{ asset('storage/' . $item->menuItem->image_url) }}" 
                                 alt="{{ $item->menuItem->name }}">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-utensils text-gray-400"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->menuItem->name }}</h4>
                            <p class="text-gray-600 text-sm">{{ $item->menuItem->description }}</p>
                            
                            @if($item->notes)
                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                    <div class="text-sm">
                                        <span class="font-medium text-yellow-800">Catatan:</span>
                                        <span class="text-yellow-700">{{ $item->notes }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">{{ $item->quantity }}x</div>
                            <div class="text-sm text-gray-600">
                                @ Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                            <div class="text-sm font-medium text-blue-600">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">Rp {{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($order->tax_amount)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pajak:</span>
                            <span class="font-medium">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($order->service_charge)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Layanan:</span>
                            <span class="font-medium">Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span class="text-xl font-bold text-blue-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Instructions -->
            @if($order->special_instructions)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Instruksi Khusus</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div class="text-blue-800">{{ $order->special_instructions }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if($order->status === 'pending' && !$order->confirmed_at)
                        <button onclick="confirmOrder({{ $order->id }})" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-check mr-2"></i>Konfirmasi Pesanan
                        </button>
                    @elseif($order->status === 'ready')
                        <button onclick="markAsServed({{ $order->id }})" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-utensils mr-2"></i>Tandai Disajikan
                        </button>
                    @endif
                    
                    @if(in_array($order->status, ['pending', 'preparing']))
                        <a href="{{ route('waiter.orders.edit', $order) }}" 
                           class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md transition-colors inline-block text-center">
                            <i class="fas fa-edit mr-2"></i>Edit Pesanan
                        </a>
                    @endif
                    
                    <button onclick="printReceipt({{ $order->id }})" 
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak Struk
                    </button>
                    
                    <a href="{{ route('waiter.orders.index') }}" 
                       class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors inline-block text-center">
                        <i class="fas fa-list mr-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pesanan</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Pesanan:</span>
                        <span class="font-medium">#{{ $order->id }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Waktu Pesan:</span>
                        <span class="font-medium">{{ $order->created_at->format('H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-medium">{{ $order->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    @if($order->table)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Meja:</span>
                        <span class="font-medium">{{ $order->table->table_number }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                     @switch($order->status)
                                         @case('pending') bg-yellow-100 text-yellow-800 @break
                                         @case('preparing') bg-blue-100 text-blue-800 @break
                                         @case('ready') bg-green-100 text-green-800 @break
                                         @case('served') bg-gray-100 text-gray-800 @break
                                         @default bg-gray-100 text-gray-800
                                     @endswitch">
                            @switch($order->status)
                                @case('pending') Pesanan Baru @break
                                @case('preparing') Sedang Dimasak @break
                                @case('ready') Siap Disajikan @break
                                @case('served') Selesai @break
                            @endswitch
                        </span>
                    </div>

                    @if($order->estimated_completion_time)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimasi Selesai:</span>
                        <span class="font-medium">{{ $order->estimated_completion_time->format('H:i') }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Pelayan:</span>
                        <span class="font-medium">{{ $order->waiter->name ?? 'System' }}</span>
                    </div>
                </div>
            </div>

            <!-- Table Information -->
            @if($order->table)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Meja</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Meja:</span>
                        <span class="font-medium">{{ $order->table->table_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kapasitas:</span>
                        <span class="font-medium">{{ $order->table->capacity }} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Meja:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                     @switch($order->table->status)
                                         @case('available') bg-green-100 text-green-800 @break
                                         @case('occupied') bg-red-100 text-red-800 @break
                                         @case('reserved') bg-yellow-100 text-yellow-800 @break
                                         @case('cleaning') bg-blue-100 text-blue-800 @break
                                         @default bg-gray-100 text-gray-800
                                     @endswitch">
                            @switch($order->table->status)
                                @case('available') Tersedia @break
                                @case('occupied') Terisi @break
                                @case('reserved') Direservasi @break
                                @case('cleaning') Dibersihkan @break
                            @endswitch
                        </span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('waiter.tables.show', $order->table) }}" 
                       class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-md transition-colors inline-block text-center">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail Meja
                    </a>
                </div>
            </div>
            @endif

            <!-- Customer Information -->
            @if($order->customer_name)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium">{{ $order->customer_name }}</span>
                    </div>
                    
                    @if($order->customer_phone)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Telepon:</span>
                        <span class="font-medium">{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                    
                    @if($order->customer_email)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $order->customer_email }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmOrder(orderId) {
        if (confirm('Konfirmasi pesanan ini? Pesanan akan dikirim ke dapur.')) {
            fetch(`/waiter/orders/${orderId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }
    }

    function markAsServed(orderId) {
        if (confirm('Tandai pesanan ini sebagai telah disajikan?')) {
            fetch(`/waiter/orders/${orderId}/mark-served`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }
    }

    function printReceipt(orderId) {
        // Open print receipt in new window
        window.open(`/waiter/orders/${orderId}/receipt`, '_blank', 'width=400,height=600');
    }
</script>
@endpush
@endsection
