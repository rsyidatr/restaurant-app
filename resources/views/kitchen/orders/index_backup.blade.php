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

    <!-- Advanced Filters (Optional) -->
    <div class="bg-white rounded-xl shadow-lg p-6" id="advanced-filters" style="display: none;">
        <form method="GET" action="{{ route('kitchen.orders.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="order_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pesanan</label>
                <select name="order_type" id="order_type" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Semua Tipe</option>
                    <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                    <option value="takeaway" {{ request('order_type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                    <option value="delivery" {{ request('order_type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="flex-1 min-w-48">
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Prioritas</label>
                <select name="priority" id="priority" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Semua Prioritas</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>

            <!-- Clear Filter -->
            <div>
                <a href="{{ route('kitchen.orders.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($orders as $order)
        <div class="bg-white rounded-lg shadow border-l-4 
                    @switch($order->status)
                        @case('pending') border-yellow-400 @break
                        @case('preparing') border-blue-400 @break
                        @case('ready') border-green-400 @break
                        @case('served') border-gray-400 @break
                        @default border-gray-400
                    @endswitch"
             id="order-{{ $order->id }}">
            
            <!-- Order Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $order->created_at->format('H:i') }} - 
                            {{ $order->table ? 'Meja ' . $order->table->table_number : ucfirst(str_replace('_', ' ', $order->order_type)) }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($order->priority === 'high')
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-exclamation mr-1"></i>Prioritas Tinggi
                            </span>
                        @endif
                        <div class="text-sm text-gray-500 mt-1">
                            {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="p-4">
                <div class="space-y-3 mb-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $item->menuItem->name }}</div>
                            @if($item->notes)
                                <div class="text-sm text-gray-600">{{ $item->notes }}</div>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-semibold">{{ $item->quantity }}x</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Status Badge -->
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                 @switch($order->status)
                                     @case('pending') bg-yellow-100 text-yellow-800 @break
                                     @case('preparing') bg-blue-100 text-blue-800 @break
                                     @case('ready') bg-green-100 text-green-800 @break
                                     @case('served') bg-gray-100 text-gray-800 @break
                                     @default bg-gray-100 text-gray-800
                                 @endswitch">
                        @switch($order->status)
                            @case('pending') <i class="fas fa-clock mr-1"></i>Menunggu @break
                            @case('preparing') <i class="fas fa-fire mr-1"></i>Sedang Dimasak @break
                            @case('ready') <i class="fas fa-check mr-1"></i>Siap Saji @break
                            @case('served') <i class="fas fa-utensils mr-1"></i>Disajikan @break
                        @endswitch
                    </span>
                    
                    @if($order->estimated_completion_time)
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $order->estimated_completion_time->format('H:i') }}
                    </span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    @if($order->status === 'pending')
                        <button onclick="startCooking({{ $order->id }})" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition-colors text-sm">
                            <i class="fas fa-fire mr-1"></i>Mulai Masak
                        </button>
                    @elseif($order->status === 'preparing')
                        <button onclick="markReady({{ $order->id }})" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md transition-colors text-sm">
                            <i class="fas fa-check mr-1"></i>Siap Saji
                        </button>
                    @endif
                    
                    <a href="{{ route('kitchen.orders.show', $order) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 rounded-md transition-colors text-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-fire text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pesanan</h3>
                <p class="text-gray-600">Tidak ada pesanan yang ditemukan dengan filter yang dipilih.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="bg-white rounded-lg shadow p-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(() => {
            location.reload();
        }, 30000); // 30 seconds
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }
    
    // Start auto refresh when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
    });
    
    // Stop auto refresh when user is interacting
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });

    function startCooking(orderId) {
        fetch(`/kitchen/orders/${orderId}/start-cooking`, {
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
                
                // Update order card
                const orderCard = document.getElementById(`order-${orderId}`);
                orderCard.classList.remove('border-yellow-400');
                orderCard.classList.add('border-blue-400');
                
                // Update status badge and button
                const statusBadge = orderCard.querySelector('.inline-flex');
                statusBadge.className = 'inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800';
                statusBadge.innerHTML = '<i class="fas fa-fire mr-1"></i>Sedang Dimasak';
                
                const actionButton = orderCard.querySelector('button');
                actionButton.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md transition-colors text-sm';
                actionButton.innerHTML = '<i class="fas fa-check mr-1"></i>Siap Saji';
                actionButton.setAttribute('onclick', `markReady(${orderId})`);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }

    function markReady(orderId) {
        fetch(`/kitchen/orders/${orderId}/mark-ready`, {
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
                
                // Update order card
                const orderCard = document.getElementById(`order-${orderId}`);
                orderCard.classList.remove('border-blue-400');
                orderCard.classList.add('border-green-400');
                
                // Update status badge and remove button
                const statusBadge = orderCard.querySelector('.inline-flex');
                statusBadge.className = 'inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800';
                statusBadge.innerHTML = '<i class="fas fa-check mr-1"></i>Siap Saji';
                
                const actionButton = orderCard.querySelector('button');
                actionButton.remove();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }
</script>
@endpush
@endsection
