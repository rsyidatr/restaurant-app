@extends('layouts.waiter')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Meja {{ $table->table_number }}</h1>
            <p class="text-gray-600">Kapasitas: {{ $table->capacity }} kursi</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('waiter.tables.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Table Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Meja</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Meja:</span>
                    <span class="font-medium">{{ $table->table_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kapasitas:</span>
                    <span class="font-medium">{{ $table->capacity }} kursi</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                 @switch($table->status)
                                     @case('available') bg-green-100 text-green-800 @break
                                     @case('occupied') bg-red-100 text-red-800 @break
                                     @case('reserved') bg-yellow-100 text-yellow-800 @break
                                     @case('cleaning') bg-blue-100 text-blue-800 @break
                                     @default bg-gray-100 text-gray-800
                                 @endswitch">
                        @switch($table->status)
                            @case('available') Tersedia @break
                            @case('occupied') Terisi @break
                            @case('reserved') Direservasi @break
                            @case('cleaning') Dibersihkan @break
                            @default {{ ucfirst($table->status) }}
                        @endswitch
                    </span>
                </div>
                @if($table->description)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Deskripsi:</span>
                        <span class="font-medium">{{ $table->description }}</span>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex space-x-2">
                @if($table->status === 'occupied')
                    <button onclick="updateTableStatus('available')" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Kosongkan Meja
                    </button>
                @elseif($table->status === 'cleaning')
                    <button onclick="updateTableStatus('available')" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Selesai Bersih
                    </button>
                @elseif($table->status === 'available')
                    <button onclick="updateTableStatus('cleaning')" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Mulai Bersihkan
                    </button>
                @endif
            </div>
        </div>

        <!-- Current Order -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pesanan Aktif</h2>
            
            @if($table->currentOrder)
                <div class="space-y-4">
                    <div class="border-b pb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium">#{{ $table->currentOrder->order_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $table->currentOrder->customer_name ?? 'Customer' }}</p>
                                <p class="text-sm text-gray-600">{{ $table->currentOrder->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($table->currentOrder->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-medium mb-2">Item Pesanan:</h4>
                        <div class="space-y-2">
                            @foreach($table->currentOrder->orderItems as $item)
                                <div class="flex justify-between">
                                    <span class="text-sm">{{ $item->menu_name }} ({{ $item->quantity }}x)</span>
                                    <span class="text-sm font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t mt-2 pt-2">
                            <div class="flex justify-between font-medium">
                                <span>Total:</span>
                                <span>Rp {{ number_format($table->currentOrder->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('waiter.orders.show', $table->currentOrder) }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center">
                            Detail Pesanan
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-4"></i>
                    <p>Tidak ada pesanan aktif di meja ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Order History -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Pesanan Hari Ini</h2>
        
        @if($todayOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($todayOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->customer_name ?? 'Customer' }}</div>
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
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada riwayat pesanan hari ini</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function updateTableStatus(status) {
        if (!confirm('Yakin ingin mengubah status meja?')) {
            return;
        }

        fetch(`{{ route('waiter.tables.updateStatus', $table) }}`, {
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
                alert('Gagal mengubah status meja');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
</script>
@endpush
@endsection
