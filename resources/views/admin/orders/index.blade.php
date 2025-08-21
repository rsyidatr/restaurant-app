@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Pesanan</h1>
        <div class="flex space-x-2">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Diproses</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap</option>
                <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Disajikan</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
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
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" {{ request('table') == $table->id ? 'selected' : '' }}>{{ $table->table_number }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button onclick="refreshOrders()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($orders as $order)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
            <div class="p-4">
                <!-- Header -->
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800">#{{ $order->order_number }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Tanggal tidak tersedia' }}
                        </p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status == 'preparing') bg-orange-100 text-orange-800
                        @elseif($order->status == 'ready') bg-purple-100 text-purple-800
                        @elseif($order->status == 'served') bg-indigo-100 text-indigo-800
                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Customer & Table Info -->
                <div class="mb-3">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Pelanggan:</span> {{ $order->customer_name }}
                    </p>
                    @if($order->table)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Meja:</span> {{ $order->table->table_number }}
                        </p>
                    @endif
                    @if($order->type == 'takeaway')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                            Bawa Pulang
                        </span>
                    @endif
                </div>

                <!-- Order Items -->
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-2">Item:</p>
                    <div class="space-y-1">
                        @foreach($order->orderItems->take(3) as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item->quantity }}x {{ $item->menuItem ? $item->menuItem->name : 'Menu dihapus' }}</span>
                                <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        @if($order->orderItems->count() > 3)
                            <p class="text-xs text-gray-500">+{{ $order->orderItems->count() - 3 }} item lainnya</p>
                        @endif
                    </div>
                </div>

                <!-- Total -->
                <div class="mb-4 pt-2 border-t border-gray-200">
                    <div class="flex justify-between font-semibold">
                        <span>Total:</span>
                        <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.orders.show', $order) }}" 
                       class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                        Detail
                    </a>
                    @if($order->status == 'pending')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                Konfirmasi
                            </button>
                        </form>
                    @elseif($order->status == 'confirmed')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="preparing">
                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded text-sm">
                                Proses
                            </button>
                        </form>
                    @elseif($order->status == 'preparing')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm">
                                Siap
                            </button>
                        </form>
                    @elseif($order->status == 'ready')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="served">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded text-sm">
                                Sajikan
                            </button>
                        </form>
                    @elseif($order->status == 'served')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pesanan</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada pesanan yang masuk.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<script>
function refreshOrders() {
    location.reload();
}

// Auto refresh setiap 30 detik
setInterval(refreshOrders, 30000);

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location = url;
});

document.getElementById('searchOrder').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const search = this.value;
        const url = new URL(window.location);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        window.location = url;
    }
});

// Date filter functionality
document.getElementById('dateFilter').addEventListener('change', function() {
    const date = this.value;
    const url = new URL(window.location);
    if (date) {
        url.searchParams.set('date', date);
    } else {
        url.searchParams.delete('date');
    }
    window.location = url;
});

// Table filter functionality
document.getElementById('tableFilter').addEventListener('change', function() {
    const table = this.value;
    const url = new URL(window.location);
    if (table) {
        url.searchParams.set('table', table);
    } else {
        url.searchParams.delete('table');
    }
    window.location = url;
});
</script>
@endsection
