@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Laporan & Analitik</h1>
        <div class="space-x-2">
            <select id="dateRange" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="today">Hari Ini</option>
                <option value="week">Minggu Ini</option>
                <option value="month" selected>Bulan Ini</option>
                <option value="quarter">Kuartal Ini</option>
                <option value="year">Tahun Ini</option>
                <option value="custom">Custom</option>
            </select>
            <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Export
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
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
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_sales'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">+12% dari bulan lalu</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">+8% dari bulan lalu</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Pelanggan Unik</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['unique_customers'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">+15% dari bulan lalu</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Rata-rata Order</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['avg_order_value'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">+5% dari bulan lalu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Reports -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.reports.sales') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Laporan Penjualan</h3>
                    <p class="text-sm text-gray-500">Analisis pendapatan dan tren penjualan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.customers') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Laporan Pelanggan</h3>
                    <p class="text-sm text-gray-500">Data demografi dan perilaku pelanggan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.inventory') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Laporan Inventori</h3>
                    <p class="text-sm text-gray-500">Stok menu dan bahan baku</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.tables') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Laporan Meja</h3>
                    <p class="text-sm text-gray-500">Utilisasi dan okupansi meja</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Penjualan (7 Hari Terakhir)</h2>
            <div class="h-64 relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Popular Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Menu Terpopuler</h2>
            <div class="space-y-4">
                @if(isset($popularItems) && count($popularItems) > 0)
                    @foreach($popularItems as $index => $item)
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
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($item['revenue'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada menu yang terjual.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-4">
                @if(isset($recentActivities) && count($recentActivities) > 0)
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada aktivitas</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada aktivitas terbaru.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Peak Hours -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Jam Sibuk</h2>
            <div class="space-y-3">
                @php
                    $peakHours = [
                        ['time' => '11:00 - 12:00', 'orders' => 45, 'percentage' => 85],
                        ['time' => '12:00 - 13:00', 'orders' => 52, 'percentage' => 100],
                        ['time' => '18:00 - 19:00', 'orders' => 38, 'percentage' => 70],
                        ['time' => '19:00 - 20:00', 'orders' => 41, 'percentage' => 78],
                        ['time' => '20:00 - 21:00', 'orders' => 25, 'percentage' => 45],
                    ];
                @endphp
                @foreach($peakHours as $hour)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-900">{{ $hour['time'] }}</span>
                            <span class="text-gray-500">{{ $hour['orders'] }} pesanan</span>
                        </div>
                        <div class="mt-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $hour['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Custom Date Range Modal -->
<div id="customDateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Rentang Tanggal</h3>
            <form id="customDateForm">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari</label>
                        <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai</label>
                        <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCustomDateModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
            label: 'Penjualan (Rp)',
            data: [1200000, 1900000, 800000, 1500000, 2000000, 2200000, 1800000],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
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

// Date Range Handler
document.getElementById('dateRange').addEventListener('change', function() {
    if (this.value === 'custom') {
        showCustomDateModal();
    } else {
        updateReports(this.value);
    }
});

function showCustomDateModal() {
    document.getElementById('customDateModal').classList.remove('hidden');
}

function closeCustomDateModal() {
    document.getElementById('customDateModal').classList.add('hidden');
    document.getElementById('dateRange').value = 'month'; // reset to default
}

function updateReports(period) {
    // Redirect with period parameter
    window.location.href = `{{ route('admin.reports.index') }}?period=${period}`;
}

function exportReport() {
    const period = document.getElementById('dateRange').value;
    window.location.href = `{{ route('admin.reports.export') }}?period=${period}`;
}

// Custom date form handler
document.getElementById('customDateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (startDate && endDate) {
        window.location.href = `{{ route('admin.reports.index') }}?start_date=${startDate}&end_date=${endDate}`;
    }
});

// Close modal when clicking outside
document.getElementById('customDateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCustomDateModal();
    }
});
</script>
@endsection
