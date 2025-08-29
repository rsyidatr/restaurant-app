@extends('layouts.kitchen_simple')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Pesanan</h1>
                <p class="text-gray-600 mt-1">Monitor dan proses pesanan masuk secara real-time</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="refreshOrders()" 
                        class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all flex items-center space-x-2 shadow-lg"
                        title="Refresh Data">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
                <div class="bg-gradient-to-br from-orange-400 to-red-500 p-3 rounded-xl shadow-lg">
                    <i class="fas fa-utensils text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Menunggu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    @if(($stats['pending'] ?? 0) > 0)
                        <p class="text-xs text-orange-600 font-medium">Perlu diproses!</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg">
                    <i class="fas fa-fire text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sedang Dimasak</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['preparing'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Siap Saji</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['ready'] ?? 0 }}</p>
                    @if(($stats['ready'] ?? 0) > 0)
                        <p class="text-xs text-green-600 font-medium">Siap diantar!</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl shadow-lg">
                    <i class="fas fa-utensils text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Disajikan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['served'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('kitchen.orders.index') }}" 
               class="filter-btn {{ !request('status') ? 'active' : '' }}"
               data-status="all">
                <i class="fas fa-list mr-2"></i>
                Semua
                <span class="badge">{{ array_sum($stats ?? []) }}</span>
            </a>
            
            <a href="{{ route('kitchen.orders.index', ['status' => 'pending']) }}" 
               class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}"
               data-status="pending">
                <i class="fas fa-clock mr-2"></i>
                Menunggu
                <span class="badge bg-yellow-500">{{ $stats['pending'] ?? 0 }}</span>
            </a>
            
            <a href="{{ route('kitchen.orders.index', ['status' => 'preparing']) }}" 
               class="filter-btn {{ request('status') === 'preparing' ? 'active' : '' }}"
               data-status="preparing">
                <i class="fas fa-fire mr-2"></i>
                Sedang Dimasak
                <span class="badge bg-blue-500">{{ $stats['preparing'] ?? 0 }}</span>
            </a>
            
            <a href="{{ route('kitchen.orders.index', ['status' => 'ready']) }}" 
               class="filter-btn {{ request('status') === 'ready' ? 'active' : '' }}"
               data-status="ready">
                <i class="fas fa-check mr-2"></i>
                Siap Disajikan
                <span class="badge bg-green-500">{{ $stats['ready'] ?? 0 }}</span>
            </a>

            <a href="{{ route('kitchen.orders.index', ['status' => 'served']) }}" 
               class="filter-btn {{ request('status') === 'served' ? 'active' : '' }}"
               data-status="served">
                <i class="fas fa-utensils mr-2"></i>
                Disajikan
                <span class="badge bg-gray-500">{{ $stats['served'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    <!-- Orders Grid -->
    <div id="orders-container">
        @if(isset($orders) && $orders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($orders as $order)
                <div class="order-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" 
                     id="order-card-{{ $order->id }}" 
                     data-order-id="{{ $order->id }}"
                     data-status="{{ $order->status }}">
                    
                    <!-- Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">#{{ $order->id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->user->name ?? 'Guest' }}</p>
                                @if($order->table_number)
                                    <p class="text-xs text-orange-600 font-medium">Meja {{ $order->table_number }}</p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => 'yellow', 'icon' => 'clock', 'label' => 'Menunggu'],
                                        'preparing' => ['color' => 'blue', 'icon' => 'fire', 'label' => 'Dimasak'],
                                        'ready' => ['color' => 'green', 'icon' => 'check', 'label' => 'Siap'],
                                        'served' => ['color' => 'gray', 'icon' => 'utensils', 'label' => 'Disajikan']
                                    ];
                                    $config = $statusConfig[$order->status] ?? ['color' => 'gray', 'icon' => 'question', 'label' => $order->status];
                                @endphp
                                <span class="status-badge bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 px-3 py-1 rounded-full text-xs font-medium flex items-center">
                                    <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Time Info -->
                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $order->created_at->format('H:i') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-history mr-2"></i>
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                            @if($order->status === 'preparing' && $order->cooking_started_at)
                                <div class="flex items-center text-orange-600">
                                    <i class="fas fa-stopwatch mr-2"></i>
                                    <span class="cooking-timer" data-start="{{ $order->cooking_started_at }}">
                                        {{ now()->diffInMinutes($order->cooking_started_at) }}m
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Item Pesanan:</h4>
                        <div class="space-y-2 mb-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->menuItem->name }}</p>
                                            <p class="text-sm text-gray-600">
                                                @if($item->menuItem->category)
                                                    {{ $item->menuItem->category->name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-800 rounded-full text-sm font-bold">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Special Instructions -->
                        @if($order->special_instructions)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-sticky-note text-yellow-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">Catatan Khusus:</p>
                                        <p class="text-sm text-yellow-700">{{ $order->special_instructions }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex space-x-2">
                                @if($order->status === 'pending')
                                    <button onclick="startCooking({{ $order->id }})" 
                                            class="action-btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white"
                                            title="Mulai Masak">
                                        <i class="fas fa-fire"></i>
                                    </button>
                                @elseif($order->status === 'preparing')
                                    <button onclick="markReady({{ $order->id }})" 
                                            class="action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white"
                                            title="Tandai Siap">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @elseif($order->status === 'ready')
                                    <div class="action-btn bg-gradient-to-r from-green-100 to-green-200 text-green-700 cursor-default"
                                         title="Siap Disajikan">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                @endif
                                
                                <a href="{{ route('kitchen.orders.show', $order) }}" 
                                   class="action-btn bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white"
                                   title="Detail Pesanan">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            
                            <div class="text-sm text-gray-500">
                                Total: {{ $order->orderItems->count() }} item
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="bg-white rounded-xl shadow-lg p-6">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-utensils text-6xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pesanan</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('status'))
                        Tidak ada pesanan dengan status "{{ request('status') }}" saat ini.
                    @else
                        Belum ada pesanan yang masuk hari ini.
                    @endif
                </p>
                @if(request('status'))
                    <a href="{{ route('kitchen.orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Pesanan
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Memproses pesanan...</p>
    </div>
</div>

<style>
.filter-btn {
    @apply inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium transition-all hover:bg-gray-200;
}

.filter-btn.active {
    @apply bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg;
}

.badge {
    @apply inline-flex items-center justify-center w-6 h-6 bg-gray-400 text-white text-xs font-bold rounded-full ml-2;
}

.action-btn {
    @apply w-10 h-10 rounded-lg flex items-center justify-center font-medium transition-all transform hover:scale-105 shadow-lg;
}

.order-card {
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
}

.cooking-timer {
    font-weight: 600;
}

.status-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
</style>

<script>
let autoRefreshInterval;
let cookingTimers = [];

function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        refreshOrders();
        updateCookingTimers();
    }, 15000); // 15 seconds
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

function refreshOrders() {
    const currentUrl = new URL(window.location.href);
    
    fetch(currentUrl.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContainer = doc.getElementById('orders-container');
        if (newContainer) {
            document.getElementById('orders-container').innerHTML = newContainer.innerHTML;
            updateCookingTimers();
        }
    })
    .catch(error => {
        console.error('Error refreshing orders:', error);
        showError('Gagal memperbarui data pesanan');
    });
}

function updateCookingTimers() {
    const timers = document.querySelectorAll('.cooking-timer');
    timers.forEach(timer => {
        const startTime = new Date(timer.dataset.start);
        const now = new Date();
        const diffInMinutes = Math.floor((now - startTime) / (1000 * 60));
        timer.textContent = `${diffInMinutes}m`;
        
        // Add visual indicators for cooking time
        if (diffInMinutes > 30) {
            timer.classList.add('text-red-600');
            timer.parentElement.classList.add('animate-pulse');
        } else if (diffInMinutes > 20) {
            timer.classList.add('text-yellow-600');
        }
    });
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
    showLoading();
    
    fetch(`/kitchen/orders/${orderId}/start-cooking`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccess(data.message);
            
            // Update card status immediately
            const card = document.getElementById(`order-card-${orderId}`);
            if (card) {
                card.dataset.status = 'preparing';
                updateOrderCardStatus(card, 'preparing');
            }
            
            // Refresh after a short delay
            setTimeout(() => {
                refreshOrders();
            }, 1000);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showError('Terjadi kesalahan saat memulai memasak');
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
    showLoading();
    
    fetch(`/kitchen/orders/${orderId}/mark-ready`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccess(data.message);
            
            // Update card status immediately
            const card = document.getElementById(`order-card-${orderId}`);
            if (card) {
                card.dataset.status = 'ready';
                updateOrderCardStatus(card, 'ready');
            }
            
            // Refresh after a short delay
            setTimeout(() => {
                refreshOrders();
            }, 1000);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menandai pesanan siap');
    });
}

function updateOrderCardStatus(card, newStatus) {
    const statusBadge = card.querySelector('.status-badge');
    const actionButtons = card.querySelector('.flex.space-x-2');
    
    const statusConfig = {
        'pending': { color: 'yellow', icon: 'clock', label: 'Menunggu' },
        'preparing': { color: 'blue', icon: 'fire', label: 'Dimasak' },
        'ready': { color: 'green', icon: 'check', label: 'Siap' }
    };
    
    const config = statusConfig[newStatus];
    if (config && statusBadge) {
        statusBadge.className = `status-badge bg-${config.color}-100 text-${config.color}-800 px-3 py-1 rounded-full text-xs font-medium flex items-center`;
        statusBadge.innerHTML = `<i class="fas fa-${config.icon} mr-1"></i>${config.label}`;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
    updateCookingTimers();
    
    // Update cooking timers every minute
    setInterval(updateCookingTimers, 60000);
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
