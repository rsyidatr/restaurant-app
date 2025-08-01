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
            <h1 class="text-3xl font-bold text-gray-800">Laporan Meja</h1>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="periodFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month" selected>Bulan Ini</option>
                    <option value="quarter">Kuartal Ini</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Area</label>
                <select id="areaFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Area</option>
                    <option value="indoor">Indoor</option>
                    <option value="outdoor">Outdoor</option>
                    <option value="vip">VIP</option>
                    <option value="smoking">Smoking</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas</label>
                <select id="capacityFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Kapasitas</option>
                    <option value="2">2 Orang</option>
                    <option value="4">4 Orang</option>
                    <option value="6">6 Orang</option>
                    <option value="8+">8+ Orang</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button onclick="exportTableReport('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                Export PDF
            </button>
            <button onclick="exportTableReport('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                Export Excel
            </button>
            <button onclick="viewRealTime()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm">
                Real-time View
            </button>
        </div>
    </div>

    <!-- Table Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Meja</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tableStats['total_tables'] ?? 0 }}</p>
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
                    <p class="text-sm font-medium text-green-600">Rata-rata Okupansi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tableStats['avg_occupancy'] ?? 0 }}%</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Waktu Rata-rata</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $tableStats['avg_duration'] ?? 0 }}m</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Pendapatan/Meja</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($tableStats['avg_revenue_per_table'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Occupancy Rate Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tingkat Okupansi Harian</h2>
            <div class="h-64">
                <canvas id="occupancyChart"></canvas>
            </div>
        </div>

        <!-- Peak Hours Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Jam Sibuk</h2>
            <div class="h-64">
                <canvas id="peakHoursChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Table Performance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Performa Meja</h2>
            <div class="space-y-4">
                @if(isset($topTables) && count($topTables) > 0)
                    @foreach($topTables as $index => $table)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-medium text-blue-600">
                                {{ $table['table_number'] }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Meja {{ $table['table_number'] }}</p>
                                <p class="text-xs text-gray-500">{{ $table['location'] }} • {{ $table['capacity'] }} orang</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $table['usage_rate'] }}%</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($table['revenue'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada data performa meja.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Table Layout Current Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Meja Saat Ini</h2>
            <div class="grid grid-cols-4 gap-4">
                @if(isset($currentTableStatus) && count($currentTableStatus) > 0)
                    @foreach($currentTableStatus as $table)
                    <div class="relative">
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center text-white font-bold text-sm
                            @if($table['status'] == 'occupied') bg-red-500
                            @elseif($table['status'] == 'reserved') bg-yellow-500
                            @elseif($table['status'] == 'cleaning') bg-blue-500
                            @else bg-green-500
                            @endif">
                            {{ $table['table_number'] }}
                        </div>
                        <div class="mt-1 text-xs text-center">
                            <div class="font-medium">{{ $table['capacity'] }} orang</div>
                            @if($table['status'] == 'occupied' && isset($table['order_time']))
                                <div class="text-gray-500">{{ $table['order_time'] }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-4 text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada meja</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada meja yang dikonfigurasi.</p>
                    </div>
                @endif
            </div>
            
            <!-- Legend -->
            <div class="mt-6 flex justify-center space-x-4 text-xs">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded mr-1"></div>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded mr-1"></div>
                    <span>Dipesan</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded mr-1"></div>
                    <span>Terisi</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded mr-1"></div>
                    <span>Dibersihkan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table Analytics -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Analisis Detail Meja</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Meja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Area
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kapasitas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Kunjungan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rata-rata Durasi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tingkat Okupansi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Revenue
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($tableAnalytics) && count($tableAnalytics) > 0)
                        @foreach($tableAnalytics as $table)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ $table['table_number'] }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Meja {{ $table['table_number'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $table['description'] ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $table['location'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $table['capacity'] }} orang
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $table['total_visits'] ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $table['avg_duration'] ?? 0 }} menit
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $table['occupancy_rate'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $table['occupancy_rate'] ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($table['total_revenue'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($table['current_status'] == 'available') bg-green-100 text-green-800
                                    @elseif($table['current_status'] == 'occupied') bg-red-100 text-red-800
                                    @elseif($table['current_status'] == 'reserved') bg-yellow-100 text-yellow-800
                                    @elseif($table['current_status'] == 'cleaning') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($table['current_status'] == 'available') Tersedia
                                    @elseif($table['current_status'] == 'occupied') Terisi
                                    @elseif($table['current_status'] == 'reserved') Dipesan
                                    @elseif($table['current_status'] == 'cleaning') Dibersihkan
                                    @else {{ ucfirst($table['current_status']) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data meja</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada data analitik meja.</p>
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
// Occupancy Rate Chart
const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
const occupancyChart = new Chart(occupancyCtx, {
    type: 'line',
    data: {
        labels: @json($occupancyData['dates'] ?? []),
        datasets: [{
            label: 'Tingkat Okupansi (%)',
            data: @json($occupancyData['rates'] ?? []),
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
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + '%';
                    }
                }
            }
        }
    }
});

// Peak Hours Chart
const peakHoursCtx = document.getElementById('peakHoursChart').getContext('2d');
const peakHoursChart = new Chart(peakHoursCtx, {
    type: 'bar',
    data: {
        labels: @json($peakHoursData['hours'] ?? []),
        datasets: [{
            label: 'Jumlah Reservasi',
            data: @json($peakHoursData['counts'] ?? []),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
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

function applyFilters() {
    const period = document.getElementById('periodFilter').value;
    const area = document.getElementById('areaFilter').value;
    const capacity = document.getElementById('capacityFilter').value;
    
    const url = new URL(window.location);
    url.searchParams.set('period', period);
    if (area) url.searchParams.set('area', area);
    if (capacity) url.searchParams.set('capacity', capacity);
    
    window.location = url;
}

function exportTableReport(format) {
    const period = document.getElementById('periodFilter').value;
    const area = document.getElementById('areaFilter').value;
    const capacity = document.getElementById('capacityFilter').value;
    
    const params = new URLSearchParams({
        period: period,
        format: format
    });
    
    if (area) params.append('area', area);
    if (capacity) params.append('capacity', capacity);
    
    window.location.href = `{{ route('admin.reports.tables.export') }}?${params}`;
}

function viewRealTime() {
    window.location.href = `{{ route('admin.tables.index') }}?view=realtime`;
}

// Auto refresh every 30 seconds for real-time data
setInterval(function() {
    // Only refresh if user is viewing current status
    if (document.hasFocus()) {
        // You can implement AJAX refresh for table status here
    }
}, 30000);
</script>
@endsection
