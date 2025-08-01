@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Meja {{ $table->table_number }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.tables.edit', $table) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
            <a href="{{ route('admin.tables.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Table Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Meja</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nomor Meja</label>
                        <p class="text-2xl font-bold text-gray-800">{{ $table->table_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
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
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Kapasitas</label>
                        <p class="text-gray-800">{{ $table->capacity }} orang</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Lokasi</label>
                        <p class="text-gray-800">
                            @if($table->location == 'indoor') Indoor
                            @elseif($table->location == 'outdoor') Outdoor  
                            @elseif($table->location == 'vip') VIP Room
                            @elseif($table->location == 'terrace') Terrace
                            @elseif($table->location == 'balcony') Balcony
                            @else -
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dibuat</label>
                        <p class="text-gray-800">{{ $table->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Update</label>
                        <p class="text-gray-800">{{ $table->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                @if($table->description)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600">Deskripsi</label>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded mt-1">{{ $table->description }}</p>
                </div>
                @endif

                @if($table->features)
                    @php
                        $features = json_decode($table->features, true);
                    @endphp
                    @if($features && count($features) > 0)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Fitur Khusus</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($features as $feature)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    @if($feature == 'window_view') Pemandangan Jendela
                                    @elseif($feature == 'power_outlet') Stop Kontak
                                    @elseif($feature == 'wheelchair_accessible') Akses Kursi Roda
                                    @elseif($feature == 'privacy') Privasi Tinggi
                                    @elseif($feature == 'air_conditioning') AC
                                    @else {{ $feature }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Current Status -->
            @if($table->status == 'occupied' || $table->status == 'reserved')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Saat Ini</h2>
                
                @if($table->currentReservation)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-medium text-yellow-800 mb-2">Reservasi Aktif</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-yellow-700">Nama:</span>
                                <span class="font-medium text-yellow-900">{{ $table->currentReservation->customer_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-yellow-700">Telepon:</span>
                                <span class="font-medium text-yellow-900">{{ $table->currentReservation->customer_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-yellow-700">Waktu:</span>
                                <span class="font-medium text-yellow-900">{{ $table->currentReservation->reservation_time->format('H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-yellow-700">Jumlah Tamu:</span>
                                <span class="font-medium text-yellow-900">{{ $table->currentReservation->party_size }} orang</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.reservations.show', $table->currentReservation) }}" 
                               class="text-yellow-600 hover:text-yellow-800 text-sm">
                                Lihat Detail Reservasi →
                            </a>
                        </div>
                    </div>
                @endif

                @if($table->currentOrder)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 {{ $table->currentReservation ? 'mt-4' : '' }}">
                        <h3 class="font-medium text-red-800 mb-2">Pesanan Aktif</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-red-700">Order #:</span>
                                <span class="font-medium text-red-900">{{ $table->currentOrder->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">Pelanggan:</span>
                                <span class="font-medium text-red-900">{{ $table->currentOrder->customer_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">Total:</span>
                                <span class="font-medium text-red-900">Rp {{ number_format($table->currentOrder->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">Status:</span>
                                <span class="font-medium text-red-900">{{ ucfirst($table->currentOrder->status) }}</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.orders.show', $table->currentOrder) }}" 
                               class="text-red-600 hover:text-red-800 text-sm">
                                Lihat Detail Pesanan →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            @endif

            <!-- Recent History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Terbaru</h2>
                
                @if($recentReservations && $recentReservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentReservations as $reservation)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $reservation->customer_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $reservation->reservation_date->format('d/m/Y') }} - {{ $reservation->reservation_time->format('H:i') }}</p>
                                    <p class="text-sm text-gray-600">{{ $reservation->party_size }} orang</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($reservation->status == 'completed') bg-green-100 text-green-800
                                    @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800
                                    @elseif($reservation->status == 'no_show') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="text-center">
                            <a href="{{ route('admin.reservations.index', ['table_id' => $table->id]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Semua Riwayat →
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada riwayat reservasi</p>
                @endif
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if($table->status == 'available')
                        <a href="{{ route('admin.reservations.create', ['table_id' => $table->id]) }}" 
                           class="w-full inline-block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Buat Reservasi
                        </a>
                        
                        <a href="{{ route('admin.orders.create', ['table_id' => $table->id]) }}" 
                           class="w-full inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Buat Pesanan
                        </a>
                        
                        <form action="{{ route('admin.tables.updateStatus', $table) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="maintenance">
                            <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                                Set Maintenance
                            </button>
                        </form>
                        
                    @elseif($table->status == 'occupied')
                        <form action="{{ route('admin.tables.updateStatus', $table) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="available">
                            <button type="submit" 
                                    onclick="return confirm('Tandai meja sebagai tersedia?')"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Clear Meja
                            </button>
                        </form>
                        
                    @elseif($table->status == 'reserved')
                        @if($table->currentReservation)
                            <form action="{{ route('admin.reservations.updateStatus', $table->currentReservation) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="seated">
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                                    Check-in Pelanggan
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.tables.updateStatus', $table) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="available">
                            <button type="submit" 
                                    onclick="return confirm('Batalkan reservasi dan tandai meja tersedia?')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Batalkan Reservasi
                            </button>
                        </form>
                        
                    @elseif($table->status == 'maintenance')
                        <form action="{{ route('admin.tables.updateStatus', $table) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="available">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Aktifkan Meja
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Table Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Meja</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Reservasi:</span>
                        <span class="font-medium">{{ $stats['total_reservations'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Bulan Ini:</span>
                        <span class="font-medium">{{ $stats['this_month_reservations'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tingkat Okupansi:</span>
                        <span class="font-medium">{{ $stats['occupancy_rate'] ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Pendapatan:</span>
                        <span class="font-medium">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Visual Representation -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Visual Meja</h3>
                
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-lg border-4 text-2xl font-bold
                        @if($table->status == 'available') border-green-500 bg-green-50 text-green-700
                        @elseif($table->status == 'occupied') border-red-500 bg-red-50 text-red-700
                        @elseif($table->status == 'reserved') border-yellow-500 bg-yellow-50 text-yellow-700
                        @elseif($table->status == 'maintenance') border-gray-500 bg-gray-50 text-gray-700
                        @endif">
                        {{ $table->table_number }}
                    </div>
                    <p class="mt-2 text-sm text-gray-600">{{ $table->capacity }} orang</p>
                    @if($table->location)
                        <p class="text-xs text-gray-500">
                            @if($table->location == 'indoor') Indoor
                            @elseif($table->location == 'outdoor') Outdoor  
                            @elseif($table->location == 'vip') VIP Room
                            @elseif($table->location == 'terrace') Terrace
                            @elseif($table->location == 'balcony') Balcony
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-lg shadow p-6 border border-red-200">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Danger Zone</h3>
                
                <form action="{{ route('admin.tables.destroy', $table) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus meja ini? Tindakan ini tidak dapat dibatalkan.')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Hapus Meja
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
