@extends('layouts.waiter')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Pesanan</h1>
        <div class="flex space-x-2">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Diproses</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap</option>
                <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Disajikan</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" 
                       id="searchOrder" 
                       placeholder="Cari nomor pesanan..."
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <input type="date" 
                       id="dateFilter"
                       value="{{ request('date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select id="tableFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Meja</option>
                    @if(isset($tables) && $tables->count() > 0)
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}" {{ request('table_id') == $table->id ? 'selected' : '' }}>
                                Meja {{ $table->table_number }}
                            </option>
                        @endforeach
                    @else
                        <option disabled>Tidak ada meja tersedia</option>
                    @endif
                </select>
            </div>
            <div>
                <button onclick="refreshOrders()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Refresh
                </button>
            </div>
        </div>
    </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Siap Disajikan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['ready'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100">
                    <i class="fas fa-utensils text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['served'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('waiter.orders.index') }}" class="flex flex-wrap gap-4 items-end">
            <!-- Status Filter -->
            <div class="flex-1 min-w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan</label>
                <select name="status" id="status" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pesanan Baru</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Sedang Dimasak</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Disajikan</option>
                    <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Table Filter -->
            <div class="flex-1 min-w-48">
                <label for="table" class="block text-sm font-medium text-gray-700 mb-2">Meja</label>
                <select name="table" id="table" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Meja</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" 
                                {{ request('table') == $table->id ? 'selected' : '' }}>
                            Meja {{ $table->table_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filter -->
            <div class="flex-1 min-w-48">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="date" id="date" 
                       value="{{ request('date') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>

            <!-- Clear Filter -->
            <div>
                <a href="{{ route('waiter.orders.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pesanan Hari Ini</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50" id="order-row-{{ $order->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                            @if($order->customer_name)
                                <div class="text-sm text-gray-500">{{ $order->customer_name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->table)
                                <div class="text-sm font-medium text-gray-900">
                                    Meja {{ $order->table->table_number }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $order->table->capacity }} kursi
                                </div>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $order->orderItems->count() }} item
                            </div>
                            <div class="text-sm text-gray-500">
                                @foreach($order->orderItems->take(2) as $item)
                                    {{ $item->menuItem ? $item->menuItem->name : $item->menu_name }}@if(!$loop->last),@endif
                                @endforeach
                                @if($order->orderItems->count() > 2)
                                    +{{ $order->orderItems->count() - 2 }} lainnya
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
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
                                @switch($order->status)
                                    @case('pending') Pesanan Baru @break
                                    @case('preparing') Sedang Dimasak @break
                                    @case('ready') Siap Disajikan @break
                                    @case('served') Selesai @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('waiter.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($order->status === 'ready')
                                    <button onclick="markAsServed({{ $order->id }})" 
                                            class="text-green-600 hover:text-green-900"
                                            title="Tandai Disajikan">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @endif
                                
                                @if(in_array($order->status, ['pending', 'preparing']))
                                    <a href="{{ route('waiter.orders.edit', $order) }}" 
                                       class="text-yellow-600 hover:text-yellow-900"
                                       title="Edit Pesanan">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-4"></i>
                                <p>Tidak ada pesanan yang ditemukan.</p>
                                <a href="{{ route('waiter.orders.create') }}" 
                                   class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Buat pesanan pertama →
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh every 30 seconds for real-time updates
    let autoRefreshInterval;
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(() => {
            // Only refresh if no modals are open
            if (!document.querySelector('.modal:not(.hidden)')) {
                location.reload();
            }
        }, 30000);
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }

    // Filter functions
    function refreshOrders() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchOrder').value;
        const date = document.getElementById('dateFilter').value;
        const tableId = document.getElementById('tableFilter').value;
        
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (search) params.append('search', search);
        if (date) params.append('date', date);
        if (tableId) params.append('table_id', tableId);
        
        const url = `{{ route('waiter.orders.index') }}?${params.toString()}`;
        window.location.href = url;
    }

    // Add event listeners for real-time filtering
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
        
        // Add event listeners to filters
        document.getElementById('statusFilter').addEventListener('change', refreshOrders);
        document.getElementById('dateFilter').addEventListener('change', refreshOrders);
        document.getElementById('tableFilter').addEventListener('change', refreshOrders);
        
        // Add search functionality with debounce
        let searchTimeout;
        document.getElementById('searchOrder').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(refreshOrders, 500);
        });
    });
    
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });

    function markAsServed(orderId) {
        if (confirm('Tandai pesanan ini sebagai telah disajikan?')) {
            fetch(`/waiter/orders/${orderId}/mark-served`, {
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
                    
                    // Update row status
                    const row = document.getElementById(`order-row-${orderId}`);
                    const statusCell = row.querySelector('td:nth-child(5) span');
                    statusCell.className = 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800';
                    statusCell.textContent = 'Selesai';
                    
                    // Remove serve button
                    const serveButton = row.querySelector('button[onclick*="markAsServed"]');
                    if (serveButton) {
                        serveButton.remove();
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }
    }
</script>
@endpush
@endsection
