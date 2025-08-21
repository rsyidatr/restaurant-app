@extends('layouts.kitchen_simple')

@section('title', 'Dashboard Dapur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Dapur</h1>
                <p class="text-gray-600 mt-1">Kelola pesanan masuk dan ketersediaan menu</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-fire text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesanan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Menunggu Dimasak</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-fire text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sedang Dimasak</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $processingOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Ready Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Siap Disajikan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $readyOrders ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('kitchen.orders.index', ['status' => 'pending']) }}" 
               class="bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 rounded-lg p-4 transition-colors">
                <div class="text-center">
                    <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                    <p class="text-sm font-medium text-yellow-900">Pesanan Baru</p>
                </div>
            </a>
            
            <a href="{{ route('kitchen.orders.index', ['status' => 'processing']) }}" 
               class="bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg p-4 transition-colors">
                <div class="text-center">
                    <i class="fas fa-fire text-orange-600 text-2xl mb-2"></i>
                    <p class="text-sm font-medium text-orange-900">Sedang Dimasak</p>
                </div>
            </a>
            
            <a href="{{ route('kitchen.menu.index') }}" 
               class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 transition-colors">
                <div class="text-center">
                    <i class="fas fa-utensils text-green-600 text-2xl mb-2"></i>
                    <p class="text-sm font-medium text-green-900">Kelola Menu</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Pesanan Perlu Diproses</h2>
                <a href="{{ route('kitchen.orders.index') }}" 
                   class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders ?? [] as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->name ?? 'Guest' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @foreach($order->orderItems->take(2) as $item)
                                    <div>{{ $item->quantity }}x {{ $item->menuItem->name }}</div>
                                @endforeach
                                @if($order->orderItems->count() > 2)
                                    <div class="text-gray-500">+{{ $order->orderItems->count() - 2 }} lainnya</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'ready' => 'bg-green-100 text-green-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'processing' => 'Dimasak',
                                    'ready' => 'Siap'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('H:i') }}</div>
                            <div class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('kitchen.orders.show', $order) }}" 
                               class="text-orange-600 hover:text-orange-900">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-utensils text-gray-300 text-4xl mb-4"></i>
                                <p>Tidak ada pesanan yang perlu diproses.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
