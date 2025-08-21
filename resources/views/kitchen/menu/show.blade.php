@extends('layouts.kitchen_simple')

@section('title', 'Detail Menu')

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('kitchen.menu.index') }}" 
                   class="text-orange-600 hover:text-orange-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Menu</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap dan status ketersediaan menu</p>
                </div>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-info-circle text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start space-x-6">
                    @if($menuItem->image_url)
                        <img class="w-32 h-32 rounded-lg object-cover" 
                             src="{{ asset('storage/' . $menuItem->image_url) }}" 
                             alt="{{ $menuItem->name }}">
                    @else
                        <div class="w-32 h-32 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $menuItem->name }}</h2>
                        <p class="text-gray-600 mb-4">{{ $menuItem->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800 mt-1">
                                    {{ $menuItem->category->name }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Harga</label>
                                <span class="text-xl font-bold text-orange-600 mt-1">
                                    Rp {{ number_format($menuItem->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability Control -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontrol Ketersediaan</h3>
                
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 rounded-full {{ $menuItem->is_available ? 'bg-green-100' : 'bg-red-100' }}">
                            <i class="fas {{ $menuItem->is_available ? 'fa-check' : 'fa-times' }} 
                                      {{ $menuItem->is_available ? 'text-green-600' : 'text-red-600' }}"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">
                                Status: {{ $menuItem->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Terakhir diupdate: {{ $menuItem->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="toggleAvailability({{ $menuItem->id }})" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-toggle-on mr-2"></i>
                        {{ $menuItem->is_available ? 'Tandai Tidak Tersedia' : 'Tandai Tersedia' }}
                    </button>
                </div>
            </div>

            <!-- Order Statistics -->
            @if($orderStats->isNotEmpty())
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pesanan (30 Hari Terakhir)</h3>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $orderStats->sum('total_orders') }}</div>
                        <div class="text-sm text-blue-700">Total Pesanan</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $orderStats->sum('total_quantity') }}</div>
                        <div class="text-sm text-green-700">Total Porsi</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <div class="text-2xl font-bold text-orange-600">
                            Rp {{ number_format($orderStats->sum('total_revenue'), 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-orange-700">Total Pendapatan</div>
                    </div>
                </div>

                <!-- Daily Chart -->
                <div class="overflow-x-auto">
                    <div class="w-full h-64" id="orderChart"></div>
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
                    <button onclick="toggleAvailability({{ $menuItem->id }})" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-toggle-on mr-2"></i>
                        Toggle Ketersediaan
                    </button>
                    
                    <a href="{{ route('kitchen.menu.index') }}" 
                       class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors inline-block text-center">
                        <i class="fas fa-list mr-2"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Recent Orders -->
            @if($recentOrders->isNotEmpty())
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pesanan Terbaru</h3>
                
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 border rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">
                                Order #{{ $order->id }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium">
                                {{ $order->orderItems->where('menu_item_id', $menuItem->id)->first()->quantity ?? 0 }}x
                            </div>
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
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('kitchen.orders.index', ['menu_item' => $menuItem->id]) }}" 
                       class="text-orange-600 hover:text-orange-800 text-sm">
                        Lihat Semua Pesanan →
                    </a>
                </div>
            </div>
            @endif

            <!-- Menu Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Menu</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Menu:</span>
                        <span class="font-medium">{{ $menuItem->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-medium">{{ $menuItem->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diupdate:</span>
                        <span class="font-medium">{{ $menuItem->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($menuItem->ingredients)
                    <div>
                        <span class="text-gray-600">Bahan:</span>
                        <div class="mt-1 text-sm">{{ $menuItem->ingredients }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleAvailability(menuId) {
        fetch(`/kitchen/menu/${menuId}/toggle-availability`, {
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

    @if($orderStats->isNotEmpty())
    // Order Statistics Chart
    const ctx = document.getElementById('orderChart').getContext('2d');
    const orderData = @json($orderStats->pluck('total_orders', 'date'));
    const dates = Object.keys(orderData);
    const orders = Object.values(orderData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates.map(date => new Date(date).toLocaleDateString('id-ID', { 
                month: 'short', 
                day: 'numeric' 
            })),
            datasets: [{
                label: 'Pesanan per Hari',
                data: orders,
                borderColor: 'rgb(249, 115, 22)',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif
</script>
@endpush
@endsection
