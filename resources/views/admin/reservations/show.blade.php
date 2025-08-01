@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Reservasi</h1>
        <div class="space-x-2">
            @if(!in_array($reservation->status, ['completed', 'cancelled', 'no_show']))
                <a href="{{ route('admin.reservations.edit', $reservation) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
            @endif
            <a href="{{ route('admin.reservations.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Reservation Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Reservasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                            @if($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($reservation->status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($reservation->status == 'seated') bg-green-100 text-green-800
                            @elseif($reservation->status == 'completed') bg-gray-100 text-gray-800
                            @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800
                            @elseif($reservation->status == 'no_show') bg-orange-100 text-orange-800
                            @endif">
                            @if($reservation->status == 'pending') Pending
                            @elseif($reservation->status == 'confirmed') Dikonfirmasi
                            @elseif($reservation->status == 'seated') Sedang Makan
                            @elseif($reservation->status == 'completed') Selesai
                            @elseif($reservation->status == 'cancelled') Dibatalkan
                            @elseif($reservation->status == 'no_show') Tidak Hadir
                            @endif
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Reservasi</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $reservation->reservation_date->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Waktu Reservasi</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $reservation->reservation_time->format('H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Jumlah Tamu</label>
                        <p class="text-gray-800">{{ $reservation->party_size }} orang</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Meja</label>
                        @if($reservation->table)
                            <p class="text-gray-800">Meja {{ $reservation->table->table_number }} ({{ $reservation->table->capacity }} orang)</p>
                        @else
                            <p class="text-red-500">Belum ditentukan</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dibuat pada</label>
                        <p class="text-gray-800">{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                @if($reservation->special_requests)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600">Permintaan Khusus</label>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded mt-1">{{ $reservation->special_requests }}</p>
                </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pelanggan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $reservation->customer_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nomor Telepon</label>
                        <p class="text-gray-800">{{ $reservation->customer_phone }}</p>
                    </div>
                    
                    @if($reservation->customer_email)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-800">{{ $reservation->customer_email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Orders Associated -->
            @if($reservation->orders && $reservation->orders->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan Terkait</h2>
                
                <div class="space-y-4">
                    @foreach($reservation->orders as $order)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-gray-600">{{ $order->orderItems->count() }} items</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Detail Pesanan →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Status Actions -->
            @if(!in_array($reservation->status, ['completed', 'cancelled', 'no_show']))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if($reservation->status == 'pending')
                        <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Konfirmasi Reservasi
                            </button>
                        </form>
                    @elseif($reservation->status == 'confirmed')
                        <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="seated">
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                                Check-in Pelanggan
                            </button>
                        </form>
                    @elseif($reservation->status == 'seated')
                        <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                                Selesaikan Reservasi
                            </button>
                        </form>
                    @endif

                    @if($reservation->status == 'confirmed')
                        <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="no_show">
                            <button type="submit" 
                                    onclick="return confirm('Tandai sebagai tidak hadir?')"
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                                Tidak Hadir
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            Batalkan Reservasi
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Table Assignment -->
            @if($reservation->status == 'confirmed' && !$reservation->table_id)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Assign Meja</h3>
                
                <form action="{{ route('admin.reservations.assignTable', $reservation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <select name="table_id" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-3" required>
                        <option value="">Pilih Meja</option>
                        @foreach($availableTables as $table)
                            @if($table->capacity >= $reservation->party_size)
                                <option value="{{ $table->id }}">
                                    Meja {{ $table->table_number }} ({{ $table->capacity }} orang)
                                </option>
                            @endif
                        @endforeach
                    </select>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Assign Meja
                    </button>
                </form>
            </div>
            @endif

            <!-- Create Order -->
            @if($reservation->status == 'seated')
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pesanan</h3>
                
                <a href="{{ route('admin.orders.create', ['reservation_id' => $reservation->id]) }}" 
                   class="w-full inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    Buat Pesanan
                </a>
            </div>
            @endif

            <!-- Contact Customer -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Hubungi Pelanggan</h3>
                
                <div class="space-y-2">
                    <a href="tel:{{ $reservation->customer_phone }}" 
                       class="w-full inline-block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Telepon
                    </a>
                    
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reservation->customer_phone) }}" 
                       target="_blank"
                       class="w-full inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        WhatsApp
                    </a>
                    
                    @if($reservation->customer_email)
                        <a href="mailto:{{ $reservation->customer_email }}" 
                           class="w-full inline-block text-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                            Email
                        </a>
                    @endif
                </div>
            </div>

            <!-- Reservation History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-medium">{{ $reservation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diperbarui:</span>
                        <span class="font-medium">{{ $reservation->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($reservation->confirmed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dikonfirmasi:</span>
                        <span class="font-medium">{{ $reservation->confirmed_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($reservation->seated_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-in:</span>
                        <span class="font-medium">{{ $reservation->seated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($reservation->completed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Selesai:</span>
                        <span class="font-medium">{{ $reservation->completed_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
