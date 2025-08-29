@extends('layouts.waiter_simple')

@section('title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Today's Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $todayOrders ?? 0 }}</h3>
                <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $pendingOrders ?? 0 }}</h3>
                <p class="text-sm text-gray-500">Pesanan Pending</p>
            </div>
        </div>
    </div>

    <!-- Processing Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-fire text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $processingOrders ?? 0 }}</h3>
                <p class="text-sm text-gray-500">Sedang Diproses</p>
            </div>
        </div>
    </div>

    <!-- Available Tables -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chair text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $availableTables ?? 0 }}</h3>
                <p class="text-sm text-gray-500">Meja Tersedia</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-5">Pesanan Terbaru</h2>
        
        @if(isset($recentOrders) && $recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <p class="font-medium text-gray-900">Meja #{{ $order->table_number ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at ? $order->created_at->format('H:i') : 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'ready') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">Belum ada pesanan hari ini</p>
            </div>
        @endif
    </div>

    <!-- Reservations List -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-5">Reservasi Hari Ini</h2>
        
        @if(isset($reservations) && $reservations->count() > 0)
            <div class="space-y-4">
                @foreach($reservations as $reservation)
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <p class="font-medium text-gray-900">{{ $reservation->customer_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $reservation->guest_count ?? 0 }} orang</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ $reservation->reservation_time ? $reservation->reservation_time->format('H:i') : 'N/A' }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($reservation->status ?? 'pending') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-check text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">Tidak ada reservasi hari ini</p>
            </div>
        @endif
    </div>
</div>

@if(config('app.debug'))
<!-- Development Tools (Only shown in debug mode) -->
<div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow p-4 mt-6">
    <h3 class="text-lg font-semibold text-yellow-800 mb-3">
        <i class="fas fa-wrench mr-2"></i>Development Tools
    </h3>
    <div class="flex space-x-3">
        <a href="{{ route('waiter.notification-test') }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-bell mr-2"></i>Test Notifikasi
        </a>
    </div>
</div>
@endif
@endsection
