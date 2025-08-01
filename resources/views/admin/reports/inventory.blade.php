@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.reports.index') }}" 
               class="text-blue-600 hover:text-blue-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Inventori</h1>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Kategori</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Stok</label>
                <select id="stockFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="low">Stok Rendah</option>
                    <option value="out">Habis</option>
                    <option value="available">Tersedia</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urut Berdasarkan</label>
                <select id="sortFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="name">Nama Menu</option>
                    <option value="sold">Paling Laris</option>
                    <option value="stock">Stok Tersisa</option>
                    <option value="revenue">Pendapatan</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button onclick="exportInventoryReport('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                Export PDF
            </button>
            <button onclick="exportInventoryReport('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                Export Excel
            </button>
            <button onclick="updateAllStock()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm">
                Update Stok
            </button>
        </div>
    </div>

    <!-- Inventory Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Menu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $inventoryStats['total_items'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $inventoryStats['available_items'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Stok Rendah</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $inventoryStats['low_stock_items'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Habis</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $inventoryStats['out_of_stock_items'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Stock Movement Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pergerakan Stok (7 Hari)</h2>
            <div class="h-64">
                <canvas id="stockMovementChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribusi per Kategori</h2>
            <div class="h-64">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Selling Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Menu Terlaris</h2>
            <div class="space-y-4">
                @if(isset($topSellingItems) && count($topSellingItems) > 0)
                    @foreach($topSellingItems as $index => $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-medium text-blue-600">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['category'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $item['sold'] }} terjual</p>
                            <p class="text-xs text-gray-500">Stok: {{ $item['stock'] }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada data penjualan menu.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Peringatan Stok Rendah</h2>
            <div class="space-y-4">
                @if(isset($lowStockItems) && count($lowStockItems) > 0)
                    @foreach($lowStockItems as $item)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['category'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-red-600">Stok: {{ $item['stock'] }}</p>
                            <p class="text-xs text-gray-500">Min: {{ $item['min_stock'] }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Semua stok aman</h3>
                        <p class="mt-1 text-sm text-gray-500">Tidak ada item dengan stok rendah.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Inventory Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detail Inventori</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Menu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Terjual
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pendapatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($inventoryItems) && count($inventoryItems) > 0)
                        @foreach($inventoryItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item['image'])
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['description'] ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['category'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item['stock'] ?? '-' }}</div>
                                @if(isset($item['min_stock']))
                                    <div class="text-xs text-gray-500">Min: {{ $item['min_stock'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['sold_count'] ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($item['revenue'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $stock = $item['stock'] ?? 0;
                                    $minStock = $item['min_stock'] ?? 5;
                                @endphp
                                @if($stock <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Habis
                                    </span>
                                @elseif($stock <= $minStock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Stok Rendah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="updateStock({{ $item['id'] }})" 
                                        class="text-blue-600 hover:text-blue-900">Update</button>
                                <a href="{{ route('admin.menu.edit', $item['id']) }}" 
                                   class="text-green-600 hover:text-green-900">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data inventori</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada menu yang tersedia.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stok</h3>
            <form id="updateStockForm">
                <div class="mb-4">
                    <label for="current_stock" class="block text-sm font-medium text-gray-700 mb-2">Stok Saat Ini</label>
                    <input type="number" id="current_stock" disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                </div>
                <div class="mb-4">
                    <label for="new_stock" class="block text-sm font-medium text-gray-700 mb-2">Stok Baru</label>
                    <input type="number" id="new_stock" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
                <div class="mb-4">
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">Minimum Stok</label>
                    <input type="number" id="min_stock" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="stock_note" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea id="stock_note" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Alasan update stok..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeUpdateStockModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Stock Movement Chart
const stockMovementCtx = document.getElementById('stockMovementChart').getContext('2d');
const stockMovementChart = new Chart(stockMovementCtx, {
    type: 'line',
    data: {
        labels: @json($chartData['dates'] ?? []),
        datasets: [{
            label: 'Stok Masuk',
            data: @json($chartData['stock_in'] ?? []),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }, {
            label: 'Stok Keluar',
            data: @json($chartData['stock_out'] ?? []),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Category Distribution Chart
const categoryDistributionCtx = document.getElementById('categoryDistributionChart').getContext('2d');
const categoryDistributionChart = new Chart(categoryDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: @json($categoryData['labels'] ?? []),
        datasets: [{
            data: @json($categoryData['values'] ?? []),
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)',
                'rgb(249, 115, 22)',
                'rgb(168, 85, 247)',
                'rgb(236, 72, 153)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const stock = document.getElementById('stockFilter').value;
    const sort = document.getElementById('sortFilter').value;
    
    const url = new URL(window.location);
    if (category) url.searchParams.set('category', category);
    if (stock) url.searchParams.set('stock', stock);
    if (sort) url.searchParams.set('sort', sort);
    
    window.location = url;
}

function exportInventoryReport(format) {
    const category = document.getElementById('categoryFilter').value;
    const stock = document.getElementById('stockFilter').value;
    const sort = document.getElementById('sortFilter').value;
    
    const params = new URLSearchParams({
        format: format
    });
    
    if (category) params.append('category', category);
    if (stock) params.append('stock', stock);
    if (sort) params.append('sort', sort);
    
    window.location.href = `{{ route('admin.reports.inventory.export') }}?${params}`;
}

let currentItemId = null;

function updateStock(itemId) {
    currentItemId = itemId;
    // Here you would typically fetch current stock data from server
    // For now, we'll just show the modal
    document.getElementById('updateStockModal').classList.remove('hidden');
}

function updateAllStock() {
    // Redirect to bulk stock update page
    window.location.href = `{{ route('admin.menu.index') }}?bulk_update=true`;
}

function closeUpdateStockModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
    currentItemId = null;
}

// Update Stock Form Handler
document.getElementById('updateStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentItemId) return;
    
    const newStock = document.getElementById('new_stock').value;
    const minStock = document.getElementById('min_stock').value;
    const note = document.getElementById('stock_note').value;
    
    // Here you would send the data to server
    fetch(`/admin/menu/${currentItemId}/update-stock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            stock: newStock,
            min_stock: minStock,
            note: note
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating stock');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating stock');
    });
});

// Close modal when clicking outside
document.getElementById('updateStockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpdateStockModal();
    }
});
</script>
@endsection
