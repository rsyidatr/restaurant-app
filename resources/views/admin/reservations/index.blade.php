@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Reservasi</h1>
        <a href="{{ route('admin.reservations.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Tambah Reservasi
        </a>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" 
                       id="searchReservation" 
                       placeholder="Cari nama pelanggan..."
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="seated" {{ request('status') == 'seated' ? 'selected' : '' }}>Sedang Makan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>Tidak Hadir</option>
                </select>
            </div>
            <div>
                <input type="date" 
                       id="dateFilter"
                       value="{{ request('date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select id="tableFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Meja</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" {{ request('table_id') == $table->id ? 'selected' : '' }}>
                            Meja {{ $table->table_number }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button onclick="filterReservations()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Reservations List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Meja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal & Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Tamu
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
                    @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->customer_phone }}</div>
                                @if($reservation->customer_email)
                                    <div class="text-sm text-gray-500">{{ $reservation->customer_email }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reservation->table)
                                <div class="text-sm text-gray-900">Meja {{ $reservation->table->table_number }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->table->capacity }} orang</div>
                            @else
                                <span class="text-sm text-red-500">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reservation->reservation_time)
                                <div class="text-sm text-gray-900">{{ $reservation->reservation_time->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->reservation_time->format('H:i') }}</div>
                            @else
                                <span class="text-sm text-red-500">Waktu belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $reservation->party_size }} orang
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <!-- Detail Button -->
                                <a href="{{ route('admin.reservations.show', $reservation) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-full hover:bg-blue-100 transition-colors"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($reservation->status == 'pending')
                                    <!-- Konfirmasi Button -->
                                    <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 p-2 rounded-full hover:bg-green-100 transition-colors"
                                                title="Konfirmasi Reservasi">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @elseif($reservation->status == 'confirmed')
                                    <!-- Check-in Button -->
                                    <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="seated">
                                        <button type="submit" 
                                                class="text-purple-600 hover:text-purple-900 p-2 rounded-full hover:bg-purple-100 transition-colors"
                                                title="Check-in Customer">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>
                                    </form>
                                @elseif($reservation->status == 'seated')
                                    <!-- Selesai Button -->
                                    <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" 
                                                class="text-gray-600 hover:text-gray-900 p-2 rounded-full hover:bg-gray-100 transition-colors"
                                                title="Selesaikan Reservasi">
                                            <i class="fas fa-flag-checkered"></i>
                                        </button>
                                    </form>
                                @endif

                                @if(!in_array($reservation->status, ['completed', 'cancelled', 'no_show']))
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.reservations.edit', $reservation) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-100 transition-colors"
                                       title="Edit Reservasi">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm0 0v4a2 2 0 002 2h4a2 2 0 002-2v-4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada reservasi</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada reservasi yang terdaftar.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.reservations.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Tambah Reservasi Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($reservations->hasPages())
        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

<script>
function filterReservations() {
    const search = document.getElementById('searchReservation').value;
    const status = document.getElementById('statusFilter').value;
    const date = document.getElementById('dateFilter').value;
    const tableId = document.getElementById('tableFilter').value;
    
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('status');
    url.searchParams.delete('date');
    url.searchParams.delete('table_id');
    
    if (search) url.searchParams.set('search', search);
    if (status) url.searchParams.set('status', status);
    if (date) url.searchParams.set('date', date);
    if (tableId) url.searchParams.set('table_id', tableId);
    
    window.location = url;
}

// Enter key support for search
document.getElementById('searchReservation').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        filterReservations();
    }
});
</script>
@endsection
