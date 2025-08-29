@extends('layouts.kitchen_simple')

@section('title', 'Dashboard Dapur')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Dapur</h1>
                <p class="text-gray-600 mt-2">Pesanan hari ini - {{ now()->format('d M Y') }}</p>
            </div>
            <div class="text-orange-500">
                <i class="fas fa-fire text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($todayOrders) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Hari ini</p>
                </div>
                <div class="text-gray-400">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Menunggu</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($pendingOrders) }}</p>
                    @if($pendingOrders > 0)
                        <p class="text-xs text-orange-600 mt-1 font-medium">Perlu perhatian!</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Tidak ada antrian</p>
                    @endif
                </div>
                <div class="text-gray-400">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Sedang Dimasak</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($processingOrders) }}</p>
                    @if($processingOrders > 0)
                        <p class="text-xs text-blue-600 mt-1 font-medium">Dalam proses</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Tidak ada yang dimasak</p>
                    @endif
                </div>
                <div class="text-gray-400">
                    <i class="fas fa-fire text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Ready Orders -->
        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Siap Disajikan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($readyOrders) }}</p>
                    @if($readyOrders > 0)
                        <p class="text-xs text-green-600 mt-1 font-medium">Siap diantar!</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">Semua telah disajikan</p>
                    @endif
                </div>
                <div class="text-gray-400">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-4 py-3 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Pesanan Masuk Hari Ini</h3>
                    <p class="text-xs text-gray-500 mt-1">Daftar pesanan yang perlu diproses</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="refreshOrders()" 
                            class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-50"
                            title="Refresh Data">
                        <i class="fas fa-sync text-xs"></i>
                    </button>
                    <a href="{{ route('kitchen.orders.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2 py-1 rounded-lg hover:bg-blue-50 transition-colors">
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-4">
            @if($recentOrders->count() > 0)
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                    <div class="border border-gray-200 rounded-lg p-3 hover:border-gray-300 transition-colors">
                        <div class="flex items-start justify-between">
                            <!-- Main Content -->
                            <div class="flex-1 min-w-0 mr-3">
                                <!-- Header Info -->
                                <div class="flex items-center flex-wrap gap-2 mb-2">
                                    <h4 class="font-semibold text-gray-900 text-sm">#{{ $order->id }}</h4>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full border border-gray-300 bg-white text-gray-700">
                                        @if($order->status == 'pending') Pesanan Baru
                                        @elseif($order->status == 'preparing') Sedang Dimasak
                                        @elseif($order->status == 'ready') Siap Disajikan
                                        @elseif($order->status == 'served') Sudah Disajikan
                                        @elseif($order->status == 'completed') Selesai
                                        @else {{ ucfirst($order->status) }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $order->created_at->format('H:i') }}
                                    </span>
                                </div>
                                
                                <!-- Customer & Table Info -->
                                <div class="text-xs text-gray-600 mb-2">
                                    <span class="font-medium">{{ $order->user->name ?? 'Guest' }}</span>
                                    <span class="mx-1">•</span>
                                    @if($order->table)
                                        <span>Meja {{ $order->table->table_number }}</span>
                                    @else
                                        <span>Takeaway</span>
                                    @endif
                                    <span class="mx-1">•</span>
                                    <span>{{ $order->orderItems->count() }} item</span>
                                </div>
                                
                                <!-- Order Items - Clean List -->
                                <div class="space-y-1">
                                    @foreach($order->orderItems->take(3) as $item)
                                        <div class="flex items-center justify-between text-xs py-1 px-2 bg-gray-50 rounded border">
                                            <div class="flex items-center space-x-2 min-w-0 flex-1">
                                                <span class="flex-shrink-0 w-5 h-4 bg-gray-400 rounded text-white text-xs font-bold flex items-center justify-center">
                                                    {{ $item->quantity }}
                                                </span>
                                                <span class="font-medium text-gray-700 truncate">{{ $item->menuItem->name }}</span>
                                            </div>
                                            <span class="text-gray-600 font-medium ml-2 flex-shrink-0">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($order->orderItems->count() > 3)
                                        <div class="text-xs text-gray-500 text-center py-1 bg-gray-100 rounded border border-dashed">
                                            +{{ $order->orderItems->count() - 3 }} item lainnya
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Total -->
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-medium text-gray-600">Total:</span>
                                        <span class="font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col space-y-1.5 flex-shrink-0">
                                @if($order->status == 'pending')
                                    <button onclick="startCooking({{ $order->id }})" 
                                            class="inline-flex items-center justify-center px-2.5 py-1 border border-blue-300 rounded text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
                                            title="Mulai Memasak">
                                        <i class="fas fa-fire mr-1 text-xs"></i>
                                        Mulai
                                    </button>
                                @elseif($order->status == 'preparing')
                                    <button onclick="markReady({{ $order->id }})" 
                                            class="inline-flex items-center justify-center px-2.5 py-1 border border-green-300 rounded text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors"
                                            title="Tandai Siap">
                                        <i class="fas fa-check mr-1 text-xs"></i>
                                        Siap
                                    </button>
                                @endif
                                
                                <a href="{{ route('kitchen.orders.show', $order) }}" 
                                   class="inline-flex items-center justify-center px-2.5 py-1 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye mr-1 text-xs"></i>
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-3">
                        <i class="fas fa-clipboard-list text-3xl"></i>
                    </div>
                    <h4 class="text-base font-medium text-gray-600 mb-1">Belum Ada Pesanan</h4>
                    <p class="text-sm text-gray-500">Pesanan baru akan muncul disini</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if(config('app.debug'))
<!-- Development Tools -->
<div class="bg-white border border-gray-200 rounded-lg shadow-sm mt-6">
    <div class="px-4 py-3 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-900">
            <i class="fas fa-wrench mr-2 text-gray-400"></i>Development Tools
        </h3>
    </div>
    <div class="p-4">
        <div class="flex space-x-3">
            <a href="{{ route('kitchen.notification-test') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-bell mr-1.5 text-gray-400"></i>Test Notifikasi
            </a>
        </div>
        <p class="text-xs text-gray-500 mt-2">Tools ini hanya tersedia dalam mode development</p>
    </div>
</div>
@endif

<script>
// Auto refresh functionality
let autoRefreshInterval;

function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        refreshOrders();
    }, 30000); // 30 seconds
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

function refreshOrders() {
    location.reload();
}

function refreshDashboard() {
    showInfo('Memperbarui data dashboard...');
    setTimeout(() => {
        location.reload();
    }, 500);
}

function startCooking(orderId) {
    const confirmId = showWarning('Mulai memasak pesanan ini?', false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedStartCooking(${orderId}, '${confirmId}')" 
                class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
            Ya, Mulai
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedStartCooking(orderId, confirmId) {
    notificationManager.hide(confirmId);
    const processingId = showInfo('Memulai proses memasak...', false);
    
    fetch(`/kitchen/orders/${orderId}/start-cooking`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        notificationManager.hide(processingId);
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                location.reload(); // Refresh halaman untuk update status
            }, 1000);
        } else {
            showError(data.message || 'Terjadi kesalahan saat memulai memasak');
        }
    })
    .catch(error => {
        notificationManager.hide(processingId);
        console.error('Error:', error);
        showError('Terjadi kesalahan: ' + error.message);
    });
}

function markReady(orderId) {
    const confirmId = showWarning('Tandai pesanan ini sudah siap disajikan?', false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedMarkReady(${orderId}, '${confirmId}')" 
                class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
            Ya, Siap
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedMarkReady(orderId, confirmId) {
    notificationManager.hide(confirmId);
    const processingId = showInfo('Menandai pesanan siap disajikan...', false);
    
    fetch(`/kitchen/orders/${orderId}/mark-ready`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        notificationManager.hide(processingId);
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                location.reload(); // Refresh halaman untuk update status
            }, 1000);
        } else {
            showError(data.message || 'Terjadi kesalahan saat menandai siap');
        }
    })
    .catch(error => {
        notificationManager.hide(processingId);
        console.error('Error:', error);
        showError('Terjadi kesalahan: ' + error.message);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
});

document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopAutoRefresh();
    } else {
        startAutoRefresh();
    }
});
</script>
@endsection
