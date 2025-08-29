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

    <!-- Status Order Cards in One Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesanan Baru</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-fire text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sedang Dimasak</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['preparing'] ?? 0 }}</p>
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
                                
                                @if($order->status === 'pending')
                                    <button onclick="confirmOrder({{ $order->id }})" 
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Konfirmasi Pesanan">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                @endif

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

    // Add event listeners
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

    function confirmOrder(orderId) {
        // Show confirmation notification first
        const confirmId = showWarning('Yakin ingin mengkonfirmasi pesanan ini? Pesanan akan dikirim ke dapur.', false);
        
        // Create custom confirmation dialog
        const notification = document.getElementById(`notification-${confirmId}`);
        const content = notification.querySelector('.notification-content');
        
        // Add confirm/cancel buttons
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'flex space-x-2 mt-3';
        buttonContainer.innerHTML = `
            <button onclick="proceedConfirmOrder(${orderId}, '${confirmId}')" 
                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                Ya, Konfirmasi
            </button>
            <button onclick="notificationManager.hide('${confirmId}')" 
                    class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
                Batal
            </button>
        `;
        content.appendChild(buttonContainer);
    }

    function proceedConfirmOrder(orderId, confirmId) {
        // Hide confirmation dialog
        notificationManager.hide(confirmId);
        
        // Show processing notification
        const processingId = showInfo('Mengkonfirmasi pesanan...', false);
        
        fetch(`/waiter/orders/${orderId}/confirm`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide processing notification
            notificationManager.hide(processingId);
            
            if (data.success) {
                showSuccess(data.message);
                
                // Update row status
                const row = document.getElementById(`order-row-${orderId}`);
                const statusCell = row.querySelector('td:nth-child(5) span');
                statusCell.className = 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800';
                statusCell.textContent = 'Sedang Dimasak';
                
                // Update action buttons
                const actionContainer = row.querySelector('td:last-child .flex');
                const confirmButton = actionContainer.querySelector('button[onclick*="confirmOrder"]');
                if (confirmButton) {
                    confirmButton.remove();
                }
                
                // Refresh the page after a short delay to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            // Hide processing notification
            notificationManager.hide(processingId);
            console.error('Error:', error);
            showError('Terjadi kesalahan saat mengkonfirmasi pesanan');
        });
    }

    function markAsServed(orderId) {
        // Show confirmation notification first
        const confirmId = showWarning('Yakin ingin menandai pesanan ini sebagai telah disajikan?', false);
        
        // Create custom confirmation dialog
        const notification = document.getElementById(`notification-${confirmId}`);
        const content = notification.querySelector('.notification-content');
        
        // Add confirm/cancel buttons
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'flex space-x-2 mt-3';
        buttonContainer.innerHTML = `
            <button onclick="proceedMarkAsServed(${orderId}, '${confirmId}')" 
                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                Ya, Sudah Disajikan
            </button>
            <button onclick="notificationManager.hide('${confirmId}')" 
                    class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
                Batal
            </button>
        `;
        content.appendChild(buttonContainer);
    }

    function proceedMarkAsServed(orderId, confirmId) {
        // Hide confirmation dialog
        notificationManager.hide(confirmId);
        
        // Show processing notification
        const processingId = showInfo('Menandai pesanan sebagai disajikan...', false);
        
        fetch(`/waiter/orders/${orderId}/mark-served`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide processing notification
            notificationManager.hide(processingId);
            
            if (data.success) {
                showSuccess(data.message);
                
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
                showError(data.message);
            }
        })
        .catch(error => {
            // Hide processing notification
            notificationManager.hide(processingId);
            console.error('Error:', error);
            showError('Terjadi kesalahan');
        });
    }
</script>
@endpush
@endsection
