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
            <h1 class="text-3xl font-bold text-gray-800">Laporan Pelanggan</h1>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="periodFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="month" selected>Bulan Ini</option>
                    <option value="quarter">Kuartal Ini</option>
                    <option value="year">Tahun Ini</option>
                    <option value="all">Semua Waktu</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pelanggan</label>
                <select id="customerTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua</option>
                    <option value="new">Pelanggan Baru</option>
                    <option value="returning">Pelanggan Setia</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Pesanan</label>
                <input type="number" id="minOrdersFilter" placeholder="0" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button onclick="exportCustomerReport('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                Export PDF
            </button>
            <button onclick="exportCustomerReport('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                Export Excel
            </button>
        </div>
    </div>

    <!-- Customer Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Pelanggan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $customerStats['total_customers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Pelanggan Baru</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $customerStats['new_customers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Pelanggan Setia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $customerStats['returning_customers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Rata-rata Pengeluaran</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($customerStats['avg_spending'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Customer Growth Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pertumbuhan Pelanggan</h2>
            <div class="h-64">
                <canvas id="customerGrowthChart"></canvas>
            </div>
        </div>

        <!-- Customer Segmentation -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Segmentasi Pelanggan</h2>
            <div class="h-64">
                <canvas id="customerSegmentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pelanggan Teratas</h2>
            <div class="space-y-4">
                @if(isset($topCustomers) && count($topCustomers) > 0)
                    @foreach($topCustomers as $index => $customer)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-medium text-blue-600">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $customer['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $customer['orders_count'] }} pesanan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($customer['total_spent'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $customer['last_order'] }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada data pelanggan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Demographics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Demografi Pelanggan</h2>
            <div class="space-y-4">
                <!-- Age Groups -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Kelompok Usia</h3>
                    <div class="space-y-2">
                        @php
                            $ageGroups = [
                                ['range' => '18-25', 'count' => 45, 'percentage' => 30],
                                ['range' => '26-35', 'count' => 68, 'percentage' => 45],
                                ['range' => '36-45', 'count' => 30, 'percentage' => 20],
                                ['range' => '46+', 'count' => 7, 'percentage' => 5],
                            ];
                        @endphp
                        @foreach($ageGroups as $group)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $group['range'] }} tahun</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $group['percentage'] }}%"></div>
                                </div>
                                <span class="text-sm text-gray-900 w-8">{{ $group['count'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Visit Frequency -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Frekuensi Kunjungan</h3>
                    <div class="space-y-2">
                        @php
                            $frequencies = [
                                ['label' => 'Mingguan', 'count' => 25, 'percentage' => 17],
                                ['label' => 'Bulanan', 'count' => 85, 'percentage' => 57],
                                ['label' => 'Kadang-kadang', 'count' => 40, 'percentage' => 26],
                            ];
                        @endphp
                        @foreach($frequencies as $freq)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $freq['label'] }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $freq['percentage'] }}%"></div>
                                </div>
                                <span class="text-sm text-gray-900 w-8">{{ $freq['count'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Pelanggan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Belanja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kunjungan Terakhir
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($customers) && count($customers) > 0)
                        @foreach($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">
                                                {{ strtoupper(substr($customer['name'], 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer['name'] }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $customer['id'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $customer['email'] ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $customer['phone'] ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer['orders_count'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($customer['total_spent'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer['last_order_date'] ? \Carbon\Carbon::parse($customer['last_order_date'])->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($customer['status'] == 'vip') bg-yellow-100 text-yellow-800
                                    @elseif($customer['status'] == 'returning') bg-green-100 text-green-800
                                    @elseif($customer['status'] == 'new') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($customer['status'] == 'vip') VIP
                                    @elseif($customer['status'] == 'returning') Setia
                                    @elseif($customer['status'] == 'new') Baru
                                    @else Regular
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data pelanggan</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada pelanggan yang terdaftar.</p>
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
// Customer Growth Chart
const customerGrowthCtx = document.getElementById('customerGrowthChart').getContext('2d');
const customerGrowthChart = new Chart(customerGrowthCtx, {
    type: 'line',
    data: {
        labels: @json($growthData['months'] ?? []),
        datasets: [{
            label: 'Pelanggan Baru',
            data: @json($growthData['new_customers'] ?? []),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Total Pelanggan',
            data: @json($growthData['total_customers'] ?? []),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: false
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

// Customer Segment Chart
const customerSegmentCtx = document.getElementById('customerSegmentChart').getContext('2d');
const customerSegmentChart = new Chart(customerSegmentCtx, {
    type: 'doughnut',
    data: {
        labels: @json($segmentData['labels'] ?? []),
        datasets: [{
            data: @json($segmentData['values'] ?? []),
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)', 
                'rgb(249, 115, 22)',
                'rgb(168, 85, 247)'
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
    const period = document.getElementById('periodFilter').value;
    const customerType = document.getElementById('customerTypeFilter').value;
    const minOrders = document.getElementById('minOrdersFilter').value;
    
    const url = new URL(window.location);
    url.searchParams.set('period', period);
    if (customerType) url.searchParams.set('customer_type', customerType);
    if (minOrders) url.searchParams.set('min_orders', minOrders);
    
    window.location = url;
}

function exportCustomerReport(format) {
    const period = document.getElementById('periodFilter').value;
    const customerType = document.getElementById('customerTypeFilter').value;
    const minOrders = document.getElementById('minOrdersFilter').value;
    
    const params = new URLSearchParams({
        period: period,
        format: format
    });
    
    if (customerType) params.append('customer_type', customerType);
    if (minOrders) params.append('min_orders', minOrders);
    
    window.location.href = `{{ route('admin.reports.customers.export') }}?${params}`;
}
</script>
@endsection
