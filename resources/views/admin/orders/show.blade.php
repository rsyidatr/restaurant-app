@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan #{{ $order->order_number }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.orders.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pesanan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nomor Pesanan</label>
                        <p class="text-lg font-semibold text-gray-800">#{{ $order->order_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Pelanggan</label>
                        <p class="text-gray-800">{{ $order->customer_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Telepon</label>
                        <p class="text-gray-800">{{ $order->customer_phone ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tipe Pesanan</label>
                        <p class="text-gray-800">{{ $order->type == 'dine_in' ? 'Dine In' : 'Takeaway' }}</p>
                    </div>
                    
                    @if($order->table)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Meja</label>
                        <p class="text-gray-800">{{ $order->table->table_number }} ({{ $order->table->capacity }} orang)</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Waktu Pesanan</label>
                        <p class="text-gray-800">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Update</label>
                        <p class="text-gray-800">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600">Catatan</label>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Item Pesanan</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->menuItem && $item->menuItem->image_url)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $item->menuItem->image_url }}" alt="{{ $item->menuItem->name }}">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-xs text-gray-500">{{ $item->menuItem ? substr($item->menuItem->name, 0, 2) : 'N/A' }}</span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->menuItem ? $item->menuItem->name : 'Menu dihapus' }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->menuItem && $item->menuItem->category ? $item->menuItem->category->name : 'Kategori tidak tersedia' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($item->notes)
                            <tr>
                                <td colspan="4" class="px-6 py-2 text-sm text-gray-500 italic">
                                    Catatan: {{ $item->notes }}
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total:</td>
                                <td class="px-6 py-4 text-sm font-bold text-blue-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Status Update -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
                
                @if(!in_array($order->status, ['completed', 'cancelled']))
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Diproses</option>
                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Siap</option>
                        <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Disajikan</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Update Status
                    </button>
                </form>
                @else
                <p class="text-sm text-gray-600">Pesanan sudah {{ $order->status == 'completed' ? 'selesai' : 'dibatalkan' }}</p>
                @endif
            </div>

            <!-- Quick Actions -->
            @if(!in_array($order->status, ['completed', 'cancelled']))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-2">
                    @if($order->status == 'pending')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Konfirmasi Pesanan
                            </button>
                        </form>
                    @elseif($order->status == 'confirmed')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="preparing">
                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                                Mulai Memasak
                            </button>
                        </form>
                    @elseif($order->status == 'preparing')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                                Pesanan Siap
                            </button>
                        </form>
                    @elseif($order->status == 'ready')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="served">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                                Pesanan Disajikan
                            </button>
                        </form>
                    @elseif($order->status == 'served')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Selesaikan Pesanan
                            </button>
                        </form>
                    @endif
                    
                    @if(!in_array($order->status, ['completed', 'cancelled']))
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- Payment Info -->
            @if($order->payment)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Metode:</span>
                        <span class="text-sm font-medium">{{ ucfirst($order->payment->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="text-sm font-medium 
                            @if($order->payment->status == 'paid') text-green-600
                            @elseif($order->payment->status == 'pending') text-yellow-600
                            @else text-red-600
                            @endif">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah:</span>
                        <span class="text-sm font-medium">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                    </div>
                    @if($order->payment->paid_at)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Dibayar:</span>
                        <span class="text-sm font-medium">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Print Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Cetak</h3>
                
                <div class="space-y-2">
                    <button onclick="printOrder()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Cetak Nota
                    </button>
                    <button onclick="printKitchen()" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                        Cetak Dapur
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printOrder() {
    window.open('{{ route("admin.orders.print", $order) }}', '_blank');
}

function printKitchen() {
    window.open('{{ route("admin.orders.printKitchen", $order) }}', '_blank');
}
</script>
@endsection
