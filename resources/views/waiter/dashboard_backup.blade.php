@extends('layouts.waiter_simple')

@section('title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Today's Orders -->
    <div                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Avg. Waktu Layanan</span>
                    <span class="text-sm font-medium text-blue-600">{{ $avgServiceTime ?? '15' }}m</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-5">Aksi Cepat</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">" rounded-lg shadow p-6">
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
                <p class="text-sm text-gray-500">Pesanan Menunggu</p>
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
                <p class="text-xs text-gray-400">Occupied: {{ $occupiedTables ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
        </div>
        <div class="p-6">
            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">#{{ $order->id }} - {{ $order->customer_name ?? 'Guest' }}</h4>
                            <p class="text-sm text-gray-500">
                                @if($order->table)
                                    Meja {{ $order->table->table_number }}
                                @else
                                    Takeaway
                                @endif
                            </p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->status == 'served') bg-green-100 text-green-800
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'preparing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @php
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'preparing' => 'Sedang Dimasak',
                                        'ready' => 'Siap Disajikan',
                                        'served' => 'Selesai',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                @endphp
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada pesanan hari ini.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('waiter.orders.index', ['status' => 'pending']) }}" 
                   class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-clock text-yellow-500 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Pesanan Menunggu</span>
                    <span class="text-xs text-gray-500">{{ $pendingOrders ?? 0 }} pesanan</span>
                </a>
                
                <a href="{{ route('waiter.orders.index', ['status' => 'ready']) }}" 
                   class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Siap Disajikan</span>
                    <span class="text-xs text-gray-500">Konfirmasi pesanan</span>
                </a>
                
                <a href="{{ route('waiter.tables.index') }}" 
                   class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chair text-blue-500 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Kelola Meja</span>
                    <span class="text-xs text-gray-500">{{ $availableTables ?? 0 }} tersedia</span>
                </a>
                
                <a href="{{ route('waiter.reservations.index') }}" 
                   class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-calendar-check text-purple-500 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Reservasi</span>
                    <span class="text-xs text-gray-500">{{ $todayReservations ?? 0 }} hari ini</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Today's Summary -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Status Summary -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Status Pesanan Hari Ini</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Menunggu</span>
                    <span class="text-sm font-medium text-yellow-600">{{ $pendingOrders ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Sedang Diproses</span>
                    <span class="text-sm font-medium text-blue-600">{{ $processingOrders ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Siap Disajikan</span>
                    <span class="text-sm font-medium text-green-600">{{ $readyOrders ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Selesai</span>
                    <span class="text-sm font-medium text-gray-600">{{ $servedOrders ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table Status -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Status Meja</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Tersedia</span>
                    <span class="text-sm font-medium text-green-600">{{ $availableTables ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Terisi</span>
                    <span class="text-sm font-medium text-red-600">{{ $occupiedTables ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Reserved</span>
                    <span class="text-sm font-medium text-blue-600">{{ $reservedTables ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Today's Performance -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Performa Hari Ini</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Pesanan</span>
                    <span class="text-sm font-medium text-gray-900">{{ $todayOrders ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Reservasi</span>
                    <span class="text-sm font-medium text-purple-600">{{ $todayReservations ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Avg. Waktu Layanan</span>
                    <span class="text-sm font-medium text-blue-600">{{ $avgServiceTime ?? '15' }}m</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

    <!-- Quick Actions -->
    <div style="background: white !important; border-radius: 12px !important; padding: 24px !important; border: 1px solid #e5e7eb !important; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;">
        <h2 style="font-size: 18px !important; font-weight: 600 !important; color: #1f2937 !important; margin-bottom: 20px !important;">Aksi Cepat</h2>
        
        <div style="display: grid !important; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important; gap: 16px !important;">
            <a href="{{ route('waiter.orders.index') }}" 
               style="background: #f9fafb !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; padding: 16px !important; text-decoration: none !important; transition: all 0.2s ease !important; display: block !important; text-align: center !important;"
               onmouseover="this.style.backgroundColor='#f3f4f6'"
               onmouseout="this.style.backgroundColor='#f9fafb'">
                <i class="fas fa-clipboard-list" style="font-size: 24px !important; color: #6b7280 !important; margin-bottom: 8px !important; display: block !important;"></i>
                <p style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important; margin: 0 !important;">Kelola Pesanan</p>
            </a>
            
            <a href="{{ route('waiter.tables.index') }}" 
               style="background: #f9fafb !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; padding: 16px !important; text-decoration: none !important; transition: all 0.2s ease !important; display: block !important; text-align: center !important;"
               onmouseover="this.style.backgroundColor='#f3f4f6'"
               onmouseout="this.style.backgroundColor='#f9fafb'">
                <i class="fas fa-chair" style="font-size: 24px !important; color: #6b7280 !important; margin-bottom: 8px !important; display: block !important;"></i>
                <p style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important; margin: 0 !important;">Kelola Meja</p>
            </a>
            
            <a href="{{ route('waiter.reservations.index') }}" 
               style="background: #f9fafb !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; padding: 16px !important; text-decoration: none !important; transition: all 0.2s ease !important; display: block !important; text-align: center !important;"
               onmouseover="this.style.backgroundColor='#f3f4f6'"
               onmouseout="this.style.backgroundColor='#f9fafb'">
                <i class="fas fa-calendar-check" style="font-size: 24px !important; color: #6b7280 !important; margin-bottom: 8px !important; display: block !important;"></i>
                <p style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important; margin: 0 !important;">Kelola Reservasi</p>
            </a>
            
            <a href="{{ route('waiter.orders.index', ['status' => 'ready']) }}" 
               style="background: #f9fafb !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; padding: 16px !important; text-decoration: none !important; transition: all 0.2s ease !important; display: block !important; text-align: center !important;"
               onmouseover="this.style.backgroundColor='#f3f4f6'"
               onmouseout="this.style.backgroundColor='#f9fafb'">
                <i class="fas fa-check-circle" style="font-size: 24px !important; color: #6b7280 !important; margin-bottom: 8px !important; display: block !important;"></i>
                <p style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important; margin: 0 !important;">Konfirmasi Pesanan</p>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div style="background: white !important; border-radius: 12px !important; padding: 24px !important; border: 1px solid #e5e7eb !important; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;">
        <div style="display: flex !important; align-items: center !important; justify-content: space-between !important; margin-bottom: 20px !important;">
            <h2 style="font-size: 18px !important; font-weight: 600 !important; color: #1f2937 !important;">Pesanan Terbaru</h2>
            <a href="{{ route('waiter.orders.index') }}" 
               style="color: #3b82f6 !important; text-decoration: none !important; font-size: 14px !important; font-weight: 500 !important;">
                Lihat Semua
            </a>
        </div>
        
        <div style="overflow-x: auto !important;">
            <table style="width: 100% !important; border-collapse: collapse !important;">
                <thead>
                    <tr style="background-color: #f9fafb !important;">
                        <th style="padding: 12px !important; text-align: left !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; text-transform: uppercase !important;">Pesanan</th>
                        <th style="padding: 12px !important; text-align: left !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; text-transform: uppercase !important;">Pelanggan</th>
                        <th style="padding: 12px !important; text-align: left !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; text-transform: uppercase !important;">Meja</th>
                        <th style="padding: 12px !important; text-align: left !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; text-transform: uppercase !important;">Status</th>
                        <th style="padding: 12px !important; text-align: left !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; text-transform: uppercase !important;">Total</th>
                        <th style="padding: 12px !important; text-align: left !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; text-transform: uppercase !important;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                    <tr style="border-bottom: 1px solid #f3f4f6 !important;">
                        <td style="padding: 12px !important;">
                            <div style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important;">#{{ $order->id }}</div>
                            <div style="font-size: 12px !important; color: #6b7280 !important;">{{ $order->orderItems->count() }} item</div>
                        </td>
                        <td style="padding: 12px !important;">
                            <div style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important;">{{ $order->customer_name ?? 'Guest' }}</div>
                            <div style="font-size: 12px !important; color: #6b7280 !important;">{{ $order->customer_phone ?? '-' }}</div>
                        </td>
                        <td style="padding: 12px !important;">
                            @if($order->table)
                                <span style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important;">Meja {{ $order->table->table_number }}</span>
                            @else
                                <span style="font-size: 14px !important; color: #6b7280 !important;">-</span>
                            @endif
                        </td>
                        <td style="padding: 12px !important;">
                            @php
                                $statusColors = [
                                    'pending' => 'background-color: #fef3c7; color: #92400e;',
                                    'preparing' => 'background-color: #dbeafe; color: #1e40af;',
                                    'ready' => 'background-color: #d1fae5; color: #065f46;',
                                    'served' => 'background-color: #f3f4f6; color: #374151;',
                                    'cancelled' => 'background-color: #fee2e2; color: #991b1b;'
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'preparing' => 'Sedang Dimasak',
                                    'ready' => 'Siap Disajikan',
                                    'served' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ];
                            @endphp
                            <span style="display: inline-block !important; padding: 4px 8px !important; font-size: 12px !important; font-weight: 500 !important; border-radius: 9999px !important; {{ $statusColors[$order->status] ?? 'background-color: #f3f4f6; color: #374151;' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="padding: 12px !important;">
                            <div style="font-size: 14px !important; font-weight: 500 !important; color: #1f2937 !important;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </td>
                        <td style="padding: 12px !important;">
                            <div style="font-size: 14px !important; color: #1f2937 !important;">{{ $order->created_at->format('H:i') }}</div>
                            <div style="font-size: 12px !important; color: #6b7280 !important;">{{ $order->created_at->format('d/m/Y') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 48px 12px !important; text-align: center !important;">
                            <div style="color: #6b7280 !important;">
                                <i class="fas fa-clipboard-list" style="font-size: 48px !important; color: #d1d5db !important; margin-bottom: 16px !important; display: block !important;"></i>
                                <p>Belum ada pesanan hari ini.</p>
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
