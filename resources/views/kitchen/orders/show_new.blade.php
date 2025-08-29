@extends('layouts.kitchen_simple')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Order Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Header -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pesanan #{{ $order->id }}</h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="text-gray-600">{{ $order->user->name ?? 'Guest' }}</span>
                        @if($order->table_number)
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-table mr-1"></i>Meja {{ $order->table_number }}
                            </span>
                        @endif
                    </div>
                </div>
                
                @php
                    $statusConfig = [
                        'pending' => ['color' => 'yellow', 'icon' => 'clock', 'label' => 'Menunggu Dimasak'],
                        'preparing' => ['color' => 'blue', 'icon' => 'fire', 'label' => 'Sedang Dimasak'],
                        'ready' => ['color' => 'green', 'icon' => 'check', 'label' => 'Siap Disajikan'],
                        'served' => ['color' => 'gray', 'icon' => 'utensils', 'label' => 'Disajikan']
                    ];
                    $config = $statusConfig[$order->status] ?? ['color' => 'gray', 'icon' => 'question', 'label' => $order->status];
                @endphp
                
                <div class="text-right">
                    <span class="inline-flex items-center px-4 py-2 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 rounded-xl font-semibold">
                        <i class="fas fa-{{ $config['icon'] }} mr-2"></i>
                        {{ $config['label'] }}
                    </span>
                    <div class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Timeline Pesanan</h3>
                <div class="space-y-4">
                    <!-- Order Created -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Pesanan Dibuat</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }}</p>
                        </div>
                    </div>

                    <!-- Cooking Started -->
                    @if($order->cooking_started_at)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-fire text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Mulai Dimasak</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->cooking_started_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Ready -->
                    @if($order->ready_at)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Siap Disajikan</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->ready_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Served -->
                    @if($order->served_at)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Disajikan</p>
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->served_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Cooking Timer -->
                @if($order->status === 'preparing' && $order->cooking_started_at)
                    <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-stopwatch text-orange-600"></i>
                                <span class="font-medium text-orange-800">Waktu Memasak</span>
                            </div>
                            <span class="text-lg font-bold text-orange-800" id="cooking-timer">
                                {{ now()->diffInMinutes($order->cooking_started_at) }} menit
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Item Pesanan</h3>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center">
                                @if($item->menuItem->image)
                                    <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                         alt="{{ $item->menuItem->name }}"
                                         class="w-full h-full object-cover rounded-xl">
                                @else
                                    <i class="fas fa-utensils text-orange-600 text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $item->menuItem->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->menuItem->category->name ?? 'Tanpa Kategori' }}</p>
                                @if($item->notes)
                                    <p class="text-sm text-orange-600 mt-1">
                                        <i class="fas fa-sticky-note mr-1"></i>{{ $item->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-orange-100 text-orange-800 rounded-full font-bold">
                                {{ $item->quantity }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Special Instructions -->
            @if($order->special_instructions)
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Catatan Khusus</h4>
                            <p class="text-yellow-700 mt-1">{{ $order->special_instructions }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-6">Aksi Cepat</h3>
            <div class="space-y-3">
                @if($order->status === 'pending')
                    <button onclick="startCooking()" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 px-4 rounded-xl font-medium transition-all transform hover:scale-105 shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-fire"></i>
                        <span>Mulai Memasak</span>
                    </button>
                @elseif($order->status === 'preparing')
                    <button onclick="markReady()" 
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-3 px-4 rounded-xl font-medium transition-all transform hover:scale-105 shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-check"></i>
                        <span>Tandai Siap</span>
                    </button>
                @elseif($order->status === 'ready')
                    <div class="w-full bg-green-100 text-green-800 py-3 px-4 rounded-xl font-medium text-center flex items-center justify-center space-x-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Siap Disajikan</span>
                    </div>
                @endif

                <a href="{{ route('kitchen.orders.index') }}" 
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-xl font-medium transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-6">Detail Pesanan</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Pesanan</span>
                    <span class="font-medium">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe Pesanan</span>
                    <span class="font-medium">
                        @switch($order->order_type)
                            @case('dine_in')
                                <i class="fas fa-chair mr-1"></i>Dine In
                                @break
                            @case('takeaway')
                                <i class="fas fa-shopping-bag mr-1"></i>Takeaway
                                @break
                            @case('delivery')
                                <i class="fas fa-truck mr-1"></i>Delivery
                                @break
                            @default
                                {{ $order->order_type }}
                        @endswitch
                    </span>
                </div>
                @if($order->table_number)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Meja</span>
                        <span class="font-medium">{{ $order->table_number }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Item</span>
                    <span class="font-medium">{{ $order->orderItems->sum('quantity') }} item</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Harga</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Navigasi Cepat</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('kitchen.orders.index', ['status' => 'pending']) }}" 
                   class="nav-btn bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border-yellow-200"
                   title="Pesanan Menunggu">
                    <i class="fas fa-clock"></i>
                </a>
                
                <a href="{{ route('kitchen.orders.index', ['status' => 'preparing']) }}" 
                   class="nav-btn bg-blue-50 hover:bg-blue-100 text-blue-700 border-blue-200"
                   title="Sedang Dimasak">
                    <i class="fas fa-fire"></i>
                </a>
                
                <a href="{{ route('kitchen.orders.index', ['status' => 'ready']) }}" 
                   class="nav-btn bg-green-50 hover:bg-green-100 text-green-700 border-green-200"
                   title="Siap Disajikan">
                    <i class="fas fa-check"></i>
                </a>
                
                <a href="{{ route('kitchen.menu.index') }}" 
                   class="nav-btn bg-purple-50 hover:bg-purple-100 text-purple-700 border-purple-200"
                   title="Kelola Menu">
                    <i class="fas fa-utensils"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.nav-btn {
    @apply flex items-center justify-center p-3 border rounded-lg transition-colors font-medium;
}
</style>

<script>
function startCooking() {
    const confirmId = showWarning('Mulai memasak pesanan ini?', false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedStartCooking('${confirmId}')" 
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

function proceedStartCooking(confirmId) {
    notificationManager.hide(confirmId);
    const processingId = showInfo('Memulai proses memasak...', false);
    
    fetch(`/kitchen/orders/{{ $order->id }}/start-cooking`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        notificationManager.hide(processingId);
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        notificationManager.hide(processingId);
        console.error('Error:', error);
        showError('Terjadi kesalahan saat memulai memasak');
    });
}

function markReady() {
    const confirmId = showWarning('Tandai pesanan ini sudah siap disajikan?', false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedMarkReady('${confirmId}')" 
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

function proceedMarkReady(confirmId) {
    notificationManager.hide(confirmId);
    const processingId = showInfo('Menandai pesanan siap disajikan...', false);
    
    fetch(`/kitchen/orders/{{ $order->id }}/mark-ready`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        notificationManager.hide(processingId);
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        notificationManager.hide(processingId);
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menandai pesanan siap');
    });
}

// Update cooking timer if preparing
@if($order->status === 'preparing' && $order->cooking_started_at)
function updateCookingTimer() {
    const startTime = new Date('{{ $order->cooking_started_at }}');
    const now = new Date();
    const diffInMinutes = Math.floor((now - startTime) / (1000 * 60));
    
    const timerElement = document.getElementById('cooking-timer');
    if (timerElement) {
        timerElement.textContent = `${diffInMinutes} menit`;
        
        // Add visual indicators
        if (diffInMinutes > 30) {
            timerElement.classList.add('text-red-600', 'animate-pulse');
        } else if (diffInMinutes > 20) {
            timerElement.classList.add('text-yellow-600');
        }
    }
}

// Update timer every minute
setInterval(updateCookingTimer, 60000);
updateCookingTimer(); // Initial call
@endif
</script>
@endsection
