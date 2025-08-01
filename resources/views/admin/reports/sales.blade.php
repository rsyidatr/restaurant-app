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
            <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h1>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="periodFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month" selected>Bulan Ini</option>
                    <option value="quarter">Kuartal Ini</option>
                    <option value="year">Tahun Ini</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" id="startDate" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" id="endDate" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select id="paymentMethodFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Metode</option>
                    <option value="cash">Cash</option>
                    <option value="card">Kartu</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button onclick="exportSalesReport('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                Export PDF
            </button>
            <button onclick="exportSalesReport('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                Export Excel
            </button>
            <button onclick="printReport()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                Print
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Total Penjualan</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($salesData['total_sales'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Pesanan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $salesData['total_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Rata-rata Order</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($salesData['avg_order_value'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Pertumbuhan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $salesData['growth_percentage'] ?? 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Sales Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Penjualan Harian</h2>
            <div class="h-64">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribusi Metode Pembayaran</h2>
            <div class="h-64">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Selling Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Menu Terlaris</h2>
            <div class="space-y-4">
                @if(isset($topItems) && count($topItems) > 0)
                    @foreach($topItems as $index => $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-medium text-blue-600">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['quantity'] }} terjual</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $item['percentage'] }}% dari total</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada item yang terjual pada periode ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sales by Category -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Penjualan per Kategori</h2>
            <div class="space-y-4">
                @if(isset($salesByCategory) && count($salesByCategory) > 0)
                    @foreach($salesByCategory as $category)
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-900">{{ $category['name'] }}</span>
                            <span class="text-gray-500">Rp {{ number_format($category['revenue'], 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada penjualan per kategori pada periode ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Sales Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detail Penjualan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Items
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Metode Bayar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($detailedSales) && count($detailedSales) > 0)
                        @foreach($detailedSales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale['date'] ? \Carbon\Carbon::parse($sale['date'])->format('d/m/Y H:i') : 'Tanggal tidak tersedia' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $sale['order_number'] }}</div>
                                <div class="text-sm text-gray-500">{{ $sale['table'] ? 'Meja ' . $sale['table'] : 'Takeaway' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale['customer_name'] ?? 'Guest' }}</div>
                                @if($sale['customer_phone'])
                                    <div class="text-sm text-gray-500">{{ $sale['customer_phone'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $sale['items_count'] }} items</div>
                                <div class="text-xs text-gray-500">{{ $sale['items_preview'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($sale['payment_method'] == 'cash') bg-green-100 text-green-800
                                    @elseif($sale['payment_method'] == 'card') bg-blue-100 text-blue-800
                                    @elseif($sale['payment_method'] == 'bank_transfer') bg-purple-100 text-purple-800
                                    @elseif($sale['payment_method'] == 'e_wallet') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($sale['payment_method'] == 'cash') Cash
                                    @elseif($sale['payment_method'] == 'card') Kartu
                                    @elseif($sale['payment_method'] == 'bank_transfer') Transfer
                                    @elseif($sale['payment_method'] == 'e_wallet') E-Wallet
                                    @else {{ ucfirst($sale['payment_method']) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($sale['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data penjualan</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada transaksi pada periode yang dipilih.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Trend Chart
const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
const salesTrendChart = new Chart(salesTrendCtx, {
    type: 'line',
    data: {
        labels: @json($chartData['dates'] ?? []),
        datasets: [{
            label: 'Penjualan (Rp)',
            data: @json($chartData['sales'] ?? []),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Payment Method Chart
const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
const paymentMethodChart = new Chart(paymentMethodCtx, {
    type: 'doughnut',
    data: {
        labels: @json($paymentMethodData['labels'] ?? []),
        datasets: [{
            data: @json($paymentMethodData['values'] ?? []),
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)', 
                'rgb(168, 85, 247)',
                'rgb(249, 115, 22)'
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

// Filter Functions
function applyFilters() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const paymentMethod = document.getElementById('paymentMethodFilter').value;
    
    const url = new URL(window.location);
    url.searchParams.set('start_date', startDate);
    url.searchParams.set('end_date', endDate);
    if (paymentMethod) url.searchParams.set('payment_method', paymentMethod);
    
    window.location = url;
}

function exportSalesReport(format) {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const paymentMethod = document.getElementById('paymentMethodFilter').value;
    
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        format: format
    });
    
    if (paymentMethod) params.append('payment_method', paymentMethod);
    
    window.location.href = `{{ route('admin.reports.sales.export') }}?${params}`;
}

function printReport() {
    window.print();
}

// Period Filter Handler
document.getElementById('periodFilter').addEventListener('change', function() {
    const period = this.value;
    const now = new Date();
    let startDate, endDate;
    
    switch(period) {
        case 'today':
            startDate = endDate = now.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(now.setDate(now.getDate() - now.getDay()));
            startDate = weekStart.toISOString().split('T')[0];
            endDate = new Date().toISOString().split('T')[0];
            break;
        case 'month':
            startDate = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
            endDate = new Date().toISOString().split('T')[0];
            break;
        case 'quarter':
            const quarter = Math.floor(now.getMonth() / 3);
            startDate = new Date(now.getFullYear(), quarter * 3, 1).toISOString().split('T')[0];
            endDate = new Date().toISOString().split('T')[0];
            break;
        case 'year':
            startDate = new Date(now.getFullYear(), 0, 1).toISOString().split('T')[0];
            endDate = new Date().toISOString().split('T')[0];
            break;
        default:
            return; // custom - don't auto-update
    }
    
    document.getElementById('startDate').value = startDate;
    document.getElementById('endDate').value = endDate;
});
</script>
@endsection
