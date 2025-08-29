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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tables->where('status', 'available')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Terisi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tables->where('status', 'occupied')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Direservasi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tables->where('status', 'reserved')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Meja</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tables->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Grid Layout -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Layout Meja Restaurant</h2>
                <p class="text-sm text-gray-600 mt-1">Status meja untuk hari ini: {{ date('d F Y') }}</p>
            </div>
        </div>

        <!-- Regular Tables Section -->
        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4">Regular Tables</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($tables->where('capacity', '<=', 20)->sortBy(function($table) {
                    return is_numeric($table->table_number) ? (int)$table->table_number : 999;
                }) as $table)
                <div class="relative group">
                    <div class="table-item border-2 rounded-lg p-4 text-center cursor-pointer transition-all duration-200 hover:shadow-lg
                        @if($table->status == 'available') border-green-500 bg-green-50 hover:bg-green-100
                        @elseif($table->status == 'occupied') border-red-500 bg-red-50 hover:bg-red-100
                        @elseif($table->status == 'reserved') border-yellow-500 bg-yellow-50 hover:bg-yellow-100
                        @elseif($table->status == 'cleaning') border-gray-500 bg-gray-50 hover:bg-gray-100
                        @endif"
                        onclick="showTableDetails({{ $table->id }})">
                        
                        <!-- Table Number -->
                        <div class="text-2xl font-bold 
                            @if($table->status == 'available') text-green-700
                            @elseif($table->status == 'occupied') text-red-700
                            @elseif($table->status == 'reserved') text-yellow-700
                            @elseif($table->status == 'cleaning') text-gray-700
                            @endif">
                            {{ $table->table_number }}
                        </div>
                        
                        <!-- Capacity -->
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $table->capacity }} orang
                        </div>
                        
                        <!-- Status -->
                        <div class="text-xs font-medium mt-2
                            @if($table->status == 'available') text-green-600
                            @elseif($table->status == 'occupied') text-red-600
                            @elseif($table->status == 'reserved') text-yellow-600
                            @elseif($table->status == 'cleaning') text-gray-600
                            @endif">
                            @if($table->status == 'available') Tersedia
                            @elseif($table->status == 'occupied') Terisi
                            @elseif($table->status == 'reserved') Direservasi
                            @elseif($table->status == 'cleaning') Dibersihkan
                            @endif
                        </div>

                        <!-- Current Order Info -->
                        @if($table->status == 'occupied' && $table->currentOrder)
                            <div class="text-xs text-gray-600 mt-1">
                                #{{ $table->currentOrder->id }}
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions for Waiter -->
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex space-x-1">
                            @if($table->status === 'occupied')
                                <button onclick="event.stopPropagation(); updateTableStatus({{ $table->id }}, 'available')" 
                                        class="w-6 h-6 bg-green-600 text-white rounded-full text-xs hover:bg-green-700"
                                        title="Kosongkan Meja">
                                    ✓
                                </button>
                            @elseif($table->status === 'cleaning')
                                <button onclick="event.stopPropagation(); updateTableStatus({{ $table->id }}, 'available')" 
                                        class="w-6 h-6 bg-green-600 text-white rounded-full text-xs hover:bg-green-700"
                                        title="Selesai Dibersihkan">
                                    ✓
                                </button>
                            @elseif($table->status === 'available')
                                <button onclick="event.stopPropagation(); updateTableStatus({{ $table->id }}, 'cleaning')" 
                                        class="w-6 h-6 bg-blue-600 text-white rounded-full text-xs hover:bg-blue-700"
                                        title="Bersihkan Meja">
                                    🧽
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- VIP Rooms Section -->
        @if($tables->where('capacity', '>', 20)->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-800 mb-4">VIP Private Dining Rooms</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($tables->where('capacity', '>', 20)->sortBy('table_number') as $table)
                <div class="relative group">
                    <div class="table-item border-2 rounded-lg p-6 text-center cursor-pointer transition-all duration-200 hover:shadow-lg
                        @if($table->status == 'available') border-green-500 bg-green-50 hover:bg-green-100
                        @elseif($table->status == 'occupied') border-red-500 bg-red-50 hover:bg-red-100
                        @elseif($table->status == 'reserved') border-yellow-500 bg-yellow-50 hover:bg-yellow-100
                        @elseif($table->status == 'cleaning') border-gray-500 bg-gray-50 hover:bg-gray-100
                        @endif"
                        onclick="showTableDetails({{ $table->id }})">
                        
                        <!-- VIP Room Number -->
                        <div class="text-3xl font-bold 
                            @if($table->status == 'available') text-green-700
                            @elseif($table->status == 'occupied') text-red-700
                            @elseif($table->status == 'reserved') text-yellow-700
                            @elseif($table->status == 'cleaning') text-gray-700
                            @endif">
                            {{ $table->table_number }}
                        </div>
                        
                        <!-- VIP Label -->
                        <div class="text-sm font-semibold text-purple-600 mt-1">
                            VIP ROOM
                        </div>
                        
                        <!-- Capacity -->
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $table->capacity }} orang
                        </div>
                        
                        <!-- Status -->
                        <div class="text-sm font-medium mt-2
                            @if($table->status == 'available') text-green-600
                            @elseif($table->status == 'occupied') text-red-600
                            @elseif($table->status == 'reserved') text-yellow-600
                            @elseif($table->status == 'cleaning') text-gray-600
                            @endif">
                            @if($table->status == 'available') Tersedia
                            @elseif($table->status == 'occupied') Terisi
                            @elseif($table->status == 'reserved') Direservasi
                            @elseif($table->status == 'cleaning') Dibersihkan
                            @endif
                        </div>

                        <!-- Description -->
                        @if($table->description)
                        <div class="text-xs text-gray-500 mt-2">
                            {{ $table->description }}
                        </div>
                        @endif

                        <!-- Current Order Info -->
                        @if($table->status == 'occupied' && $table->currentOrder)
                            <div class="text-xs text-gray-600 mt-1 font-medium">
                                Order #{{ $table->currentOrder->id }}
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex space-x-1">
                            @if($table->status === 'occupied')
                                <button onclick="event.stopPropagation(); updateTableStatus({{ $table->id }}, 'available')" 
                                        class="w-6 h-6 bg-green-600 text-white rounded-full text-xs hover:bg-green-700"
                                        title="Kosongkan Meja">
                                    ✓
                                </button>
                            @elseif($table->status === 'cleaning')
                                <button onclick="event.stopPropagation(); updateTableStatus({{ $table->id }}, 'available')" 
                                        class="w-6 h-6 bg-green-600 text-white rounded-full text-xs hover:bg-green-700"
                                        title="Selesai Dibersihkan">
                                    ✓
                                </button>
                            @elseif($table->status === 'available')
                                <button onclick="event.stopPropagation(); updateTableStatus({{ $table->id }}, 'cleaning')" 
                                        class="w-6 h-6 bg-blue-600 text-white rounded-full text-xs hover:bg-blue-700"
                                        title="Bersihkan Meja">
                                    🧽
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Empty State -->
    @if($tables->isEmpty())
    <div class="text-center py-12">
        <div class="text-gray-500">
            <i class="fas fa-table text-gray-300 text-4xl mb-4"></i>
            <p>Tidak ada meja yang ditemukan.</p>
        </div>
    </div>
    @endif

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

    // Show table details
    function showTableDetails(tableId) {
        window.location.href = `/waiter/tables/${tableId}`;
    }

    // Update table status
    function updateTableStatus(tableId, status) {
        const statusLabels = {
            'available': 'tersedia',
            'cleaning': 'sedang dibersihkan',
            'occupied': 'terisi'
        };
        
        if (!confirm(`Yakin ingin mengubah status meja menjadi ${statusLabels[status]}?`)) {
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
                // Show success notification if notification system is available
                if (typeof showSuccess === 'function') {
                    showSuccess('Status meja berhasil diubah');
                }
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                if (typeof showError === 'function') {
                    showError('Gagal mengubah status meja');
                } else {
                    alert('Gagal mengubah status meja');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showError === 'function') {
                showError('Terjadi kesalahan');
            } else {
                alert('Terjadi kesalahan');
            }
        });
    }
</script>
@endpush
@endsection
