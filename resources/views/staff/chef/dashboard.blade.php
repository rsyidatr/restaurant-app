@extends('layouts.staff_dashboard')

@section('title', 'Chef Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dasbor Chef</h1>
            <p class="text-gray-600">Kelola pesanan dan persiapan makanan</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm text-gray-600">Selamat datang,</p>
                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Selesai Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['orders_completed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Menunggu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['orders_pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Total Item Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['total_items'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Pesanan Menunggu</h2>
        </div>
        
        <div class="p-6">
            @if($pendingOrders->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingOrders as $order)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow" id="order-{{ $order->id }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">#{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600">
                                    @if($order->table)
                                        Meja {{ $order->table->table_number }}
                                    @else
                                        Takeaway
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                @if($order->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status == 'preparing') bg-yellow-100 text-yellow-800
                                @endif">
                                @if($order->status == 'confirmed') Dikonfirmasi
                                @elseif($order->status == 'preparing') Sedang Diproses
                                @endif
                            </span>
                        </div>

                        <!-- Order Items -->
                        <div class="space-y-2 mb-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $item->quantity }}x {{ $item->menuItem->name }}</span>
                                    @if($item->notes)
                                        <span class="text-gray-500">({{ $item->notes }})</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($order->special_requests)
                            <div class="mb-4 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-xs font-medium text-yellow-800">Permintaan Khusus:</p>
                                <p class="text-sm text-yellow-700">{{ $order->special_requests }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            @if($order->status == 'confirmed')
                                <button onclick="startPreparing({{ $order->id }})" 
                                        class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded text-sm">
                                    Mulai Masak
                                </button>
                            @elseif($order->status == 'preparing')
                                <button onclick="markReady({{ $order->id }})" 
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                    Siap Disajikan
                                </button>
                            @endif
                            <button onclick="showOrderDetails({{ $order->id }})" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm">
                                Detail
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p>Tidak ada pesanan yang menunggu</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4 w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="orderDetails">
            <!-- Order details will be loaded here -->
        </div>
    </div>
</div>

<script>
function startPreparing(orderId) {
    if (confirm('Mulai memproses pesanan ini?')) {
        fetch(`/chef/orders/${orderId}/start-preparing`, {
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
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function markReady(orderId) {
    if (confirm('Tandai pesanan ini sebagai siap disajikan?')) {
        fetch(`/chef/orders/${orderId}/mark-ready`, {
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
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function showOrderDetails(orderId) {
    fetch(`/chef/orders/${orderId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const order = data.order;
                let html = `
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium">Informasi Pesanan</h4>
                            <p class="text-sm text-gray-600">Nomor: ${order.order_number}</p>
                            <p class="text-sm text-gray-600">Waktu: ${new Date(order.created_at).toLocaleString('id-ID')}</p>
                            <p class="text-sm text-gray-600">Status: ${order.status}</p>
                        </div>
                        <div>
                            <h4 class="font-medium">Item Pesanan</h4>
                            <div class="space-y-2">
                `;
                
                order.order_items.forEach(item => {
                    html += `
                        <div class="flex justify-between text-sm">
                            <span>${item.quantity}x ${item.menu_item.name}</span>
                            <span>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span>
                        </div>
                    `;
                });
                
                html += `
                            </div>
                        </div>
                        ${order.special_requests ? `
                        <div>
                            <h4 class="font-medium">Permintaan Khusus</h4>
                            <p class="text-sm text-gray-600">${order.special_requests}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('orderDetails').innerHTML = html;
                document.getElementById('orderModal').classList.remove('hidden');
                document.getElementById('orderModal').classList.add('flex');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail pesanan');
        });
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.getElementById('orderModal').classList.remove('flex');
}

// Auto refresh every 30 seconds
setInterval(() => {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
</script>
@endsection
