@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Meja</h1>
        <a href="{{ route('admin.tables.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Tambah Meja
        </a>
    </div>

    <!-- Filter dan Stats -->
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['available'] ?? 0 }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['occupied'] ?? 0 }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['reserved'] ?? 0 }}</p>
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
            <h2 class="text-xl font-semibold text-gray-800">Layout Meja Restaurant</h2>
            <div class="flex space-x-2">
                <button onclick="toggleView('grid')" id="gridBtn" class="px-3 py-2 bg-blue-600 text-white rounded active">
                    Tampilan Grid
                </button>
                <button onclick="toggleView('list')" id="listBtn" class="px-3 py-2 bg-gray-300 text-gray-700 rounded">
                    Tampilan List
                </button>
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($tables as $table)
            <div class="relative">
                <div class="table-item border-2 rounded-lg p-4 text-center cursor-pointer transition-all duration-200 hover:shadow-lg
                    @if($table->status == 'available') border-green-500 bg-green-50 hover:bg-green-100
                    @elseif($table->status == 'occupied') border-red-500 bg-red-50 hover:bg-red-100
                    @elseif($table->status == 'reserved') border-yellow-500 bg-yellow-50 hover:bg-yellow-100
                    @elseif($table->status == 'maintenance') border-gray-500 bg-gray-50 hover:bg-gray-100
                    @endif"
                    onclick="showTableDetails({{ $table->id }})">
                    
                    <!-- Table Number -->
                    <div class="text-2xl font-bold 
                        @if($table->status == 'available') text-green-700
                        @elseif($table->status == 'occupied') text-red-700
                        @elseif($table->status == 'reserved') text-yellow-700
                        @elseif($table->status == 'maintenance') text-gray-700
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
                        @elseif($table->status == 'maintenance') text-gray-600
                        @endif">
                        @if($table->status == 'available') Tersedia
                        @elseif($table->status == 'occupied') Terisi
                        @elseif($table->status == 'reserved') Reserved
                        @elseif($table->status == 'maintenance') Maintenance
                        @endif
                    </div>

                    <!-- Current Reservation/Order Info -->
                    @if($table->currentReservation)
                        <div class="text-xs text-gray-600 mt-1">
                            {{ $table->currentReservation->customer_name }}
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="flex space-x-1">
                        <button onclick="event.stopPropagation(); editTable({{ $table->id }})" 
                                class="w-6 h-6 bg-blue-600 text-white rounded-full text-xs hover:bg-blue-700">
                            ✎
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- List View -->
        <div id="listView" class="hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservasi/Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tables as $table)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Meja {{ $table->table_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $table->capacity }} orang
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($table->status == 'available') bg-green-100 text-green-800
                                    @elseif($table->status == 'occupied') bg-red-100 text-red-800
                                    @elseif($table->status == 'reserved') bg-yellow-100 text-yellow-800
                                    @elseif($table->status == 'maintenance') bg-gray-100 text-gray-800
                                    @endif">
                                    @if($table->status == 'available') Tersedia
                                    @elseif($table->status == 'occupied') Terisi
                                    @elseif($table->status == 'reserved') Reserved
                                    @elseif($table->status == 'maintenance') Maintenance
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $table->location ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($table->currentReservation)
                                    <div>
                                        <div class="font-medium">{{ $table->currentReservation->customer_name }}</div>
                                        <div class="text-gray-500">{{ $table->currentReservation->reservation_time->format('H:i') }}</div>
                                    </div>
                                @elseif($table->currentOrder)
                                    <div>
                                        <div class="font-medium">Order #{{ $table->currentOrder->order_number }}</div>
                                        <div class="text-gray-500">{{ $table->currentOrder->customer_name }}</div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.tables.show', $table) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                <a href="{{ route('admin.tables.edit', $table) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                
                                @if($table->status == 'occupied')
                                    <form action="{{ route('admin.tables.updateStatus', $table) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="available">
                                        <button type="submit" class="text-green-600 hover:text-green-900">Clear</button>
                                    </form>
                                @elseif($table->status == 'maintenance')
                                    <form action="{{ route('admin.tables.updateStatus', $table) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="available">
                                        <button type="submit" class="text-green-600 hover:text-green-900">Aktifkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Keterangan Status</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Tersedia</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Terisi</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Reserved</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Maintenance</span>
            </div>
        </div>
    </div>
</div>

<!-- Table Details Modal -->
<div id="tableModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Meja</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function toggleView(viewType) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridBtn');
    const listBtn = document.getElementById('listBtn');
    
    if (viewType === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridBtn.classList.add('bg-blue-600', 'text-white');
        gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
        listBtn.classList.add('bg-gray-300', 'text-gray-700');
        listBtn.classList.remove('bg-blue-600', 'text-white');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-blue-600', 'text-white');
        listBtn.classList.remove('bg-gray-300', 'text-gray-700');
        gridBtn.classList.add('bg-gray-300', 'text-gray-700');
        gridBtn.classList.remove('bg-blue-600', 'text-white');
    }
}

function showTableDetails(tableId) {
    fetch(`/admin/tables/${tableId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = `Meja ${data.table_number}`;
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kapasitas:</span>
                        <span class="font-medium">${data.capacity} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium">${data.status_text}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lokasi:</span>
                        <span class="font-medium">${data.location || '-'}</span>
                    </div>
                    ${data.current_info ? `
                        <div class="border-t pt-3">
                            <h4 class="font-medium text-gray-900 mb-2">Info Saat Ini:</h4>
                            <div class="text-sm text-gray-600">${data.current_info}</div>
                        </div>
                    ` : ''}
                    <div class="border-t pt-3 flex space-x-2">
                        <a href="/admin/tables/${tableId}" class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                            Detail
                        </a>
                        <a href="/admin/tables/${tableId}/edit" class="flex-1 text-center bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700">
                            Edit
                        </a>
                    </div>
                </div>
            `;
            document.getElementById('tableModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail meja');
        });
}

function editTable(tableId) {
    window.location.href = `/admin/tables/${tableId}/edit`;
}

function closeModal() {
    document.getElementById('tableModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('tableModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Auto refresh every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
</script>
@endsection
