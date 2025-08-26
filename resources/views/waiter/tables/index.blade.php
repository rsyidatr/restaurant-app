@extends('layouts.waiter')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Meja</h1>
        <div class="flex space-x-2">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Terisi</option>
                <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Direservasi</option>
                <option value="cleaning" {{ request('status') == 'cleaning' ? 'selected' : '' }}>Sedang Dibersihkan</option>
            </select>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($tables as $table)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 
                        @switch($table->status)
                            @case('available') border-green-500 @break
                            @case('occupied') border-red-500 @break
                            @case('reserved') border-yellow-500 @break
                            @case('cleaning') border-blue-500 @break
                            @default border-gray-500
                        @endswitch">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Meja {{ $table->table_number }}</h3>
                        <p class="text-sm text-gray-600">{{ $table->capacity }} kursi</p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                 @switch($table->status)
                                     @case('available') bg-green-100 text-green-800 @break
                                     @case('occupied') bg-red-100 text-red-800 @break
                                     @case('reserved') bg-yellow-100 text-yellow-800 @break
                                     @case('cleaning') bg-blue-100 text-blue-800 @break
                                     @default bg-gray-100 text-gray-800
                                 @endswitch">
                        @switch($table->status)
                            @case('available') Tersedia @break
                            @case('occupied') Terisi @break
                            @case('reserved') Direservasi @break
                            @case('cleaning') Dibersihkan @break
                            @default {{ ucfirst($table->status) }}
                        @endswitch
                    </span>
                </div>

                @if($table->description)
                    <p class="text-sm text-gray-600 mb-4">{{ $table->description }}</p>
                @endif

                <!-- Current Order Info -->
                @if($table->currentOrder)
                    <div class="bg-gray-50 rounded p-3 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Pesanan Aktif:</h4>
                        <p class="text-sm text-gray-600">#{{ $table->currentOrder->order_number }}</p>
                        <p class="text-sm text-gray-600">{{ $table->currentOrder->customer_name ?? 'Customer' }}</p>
                        <p class="text-sm text-gray-600">{{ $table->currentOrder->created_at->format('H:i') }}</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('waiter.tables.show', $table) }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm text-center">
                        Detail
                    </a>
                    
                    @if($table->status === 'occupied')
                        <button onclick="updateTableStatus({{ $table->id }}, 'available')" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                            Kosongkan
                        </button>
                    @elseif($table->status === 'cleaning')
                        <button onclick="updateTableStatus({{ $table->id }}, 'available')" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                            Selesai
                        </button>
                    @elseif($table->status === 'available')
                        <button onclick="updateTableStatus({{ $table->id }}, 'cleaning')" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                            Bersihkan
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-table text-gray-300 text-4xl mb-4"></i>
                    <p>Tidak ada meja yang ditemukan.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tables->hasPages())
        <div class="mt-8">
            {{ $tables->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setInterval(function() {
        if (!document.hidden) {
            location.reload();
        }
    }, 30000);

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        const status = this.value;
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    });

    // Update table status
    function updateTableStatus(tableId, status) {
        if (!confirm('Yakin ingin mengubah status meja?')) {
            return;
        }

        fetch(`/waiter/tables/${tableId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status meja');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
</script>
@endpush
@endsection
