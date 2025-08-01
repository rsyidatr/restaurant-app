@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.payments.index') }}" 
               class="text-blue-600 hover:text-blue-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pembayaran #{{ $payment->payment_number ?? $payment->id }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Pembayaran</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($payment->status == 'paid') bg-green-100 text-green-800
                            @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status == 'failed') bg-red-100 text-red-800
                            @elseif($payment->status == 'cancelled') bg-gray-100 text-gray-800
                            @elseif($payment->status == 'refunded') bg-blue-100 text-blue-800
                            @endif">
                            @if($payment->status == 'paid') Berhasil
                            @elseif($payment->status == 'pending') Menunggu Pembayaran
                            @elseif($payment->status == 'failed') Gagal
                            @elseif($payment->status == 'cancelled') Dibatalkan
                            @elseif($payment->status == 'refunded') Refund
                            @else {{ ucfirst($payment->status) }}
                            @endif
                        </span>
                    </div>
                    <div class="space-x-2">
                        @if($payment->status == 'pending')
                            <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    Verifikasi Pembayaran
                                </button>
                            </form>
                        @endif
                        
                        @if($payment->status == 'paid')
                            <button onclick="showRefundModal()" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                Proses Refund
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Pembayaran</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $payment->payment_number ?? $payment->id }}</p>
                        </div>
                        
                        @if($payment->transaction_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Transaksi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->transaction_id }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($payment->payment_method == 'cash') Cash
                                @elseif($payment->payment_method == 'card') Kartu Kredit/Debit
                                @elseif($payment->payment_method == 'bank_transfer') Transfer Bank
                                @elseif($payment->payment_method == 'e_wallet') E-Wallet
                                @else {{ ucfirst($payment->payment_method) }}
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        
                        @if($payment->fee > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Biaya Admin</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($payment->fee, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        
                        @if($payment->paid_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Dibayar</label>
                            <p class="mt-1 text-sm text-green-600">{{ $payment->paid_at->format('d F Y, H:i') }}</p>
                        </div>
                        @endif
                        
                        @if($payment->cancelled_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Dibatalkan</label>
                            <p class="mt-1 text-sm text-red-600">{{ $payment->cancelled_at->format('d F Y, H:i') }}</p>
                        </div>
                        @endif
                        
                        @if($payment->refunded_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Refund</label>
                            <p class="mt-1 text-sm text-blue-600">{{ $payment->refunded_at->format('d F Y, H:i') }}</p>
                        </div>
                        @endif
                        
                        @if($payment->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            @if($payment->order)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pesanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Pesanan</label>
                            <a href="{{ route('admin.orders.show', $payment->order) }}" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                #{{ $payment->order->order_number }}
                            </a>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Pesanan</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->order->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($payment->order->status == 'preparing') bg-orange-100 text-orange-800
                                @elseif($payment->order->status == 'ready') bg-green-100 text-green-800
                                @elseif($payment->order->status == 'served') bg-purple-100 text-purple-800
                                @elseif($payment->order->status == 'completed') bg-green-100 text-green-800
                                @elseif($payment->order->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($payment->order->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Pesanan</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        
                        @if($payment->order->table)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meja</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->order->table->table_number }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pesanan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->order->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        
                        @if($payment->order->customer_name)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->order->customer_name }}</p>
                        </div>
                        @endif
                        
                        @if($payment->order->customer_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telepon Pelanggan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->order->customer_phone }}</p>
                        </div>
                        @endif
                        
                        @if($payment->order->special_requests)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Permintaan Khusus</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->order->special_requests }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Refund History -->
            @if($payment->refunds && $payment->refunds->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Refund</h2>
                <div class="space-y-4">
                    @foreach($payment->refunds as $refund)
                    <div class="border-l-4 border-blue-400 pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">Rp {{ number_format($refund->amount, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600">{{ $refund->reason }}</p>
                                <p class="text-xs text-gray-500">{{ $refund->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($refund->status == 'approved') bg-green-100 text-green-800
                                @elseif($refund->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($refund->status == 'rejected') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($refund->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Payment Receipt -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Struk Pembayaran</h2>
                
                <div class="space-y-3 text-sm">
                    <div class="border-b pb-3">
                        <div class="text-center">
                            <h3 class="font-bold">RESTAURANT NAME</h3>
                            <p class="text-xs text-gray-600">Jl. Alamat Restaurant</p>
                            <p class="text-xs text-gray-600">Telp: (021) 1234-5678</p>
                        </div>
                    </div>
                    
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span>No. Pembayaran:</span>
                            <span>#{{ $payment->payment_number ?? $payment->id }}</span>
                        </div>
                        @if($payment->order)
                        <div class="flex justify-between">
                            <span>No. Pesanan:</span>
                            <span>#{{ $payment->order->order_number }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Tanggal:</span>
                            <span>{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kasir:</span>
                            <span>{{ $payment->processed_by ?? 'System' }}</span>
                        </div>
                    </div>
                    
                    @if($payment->order && $payment->order->orderItems)
                    <div class="border-t border-b py-3">
                        <h4 class="font-medium mb-2">Item Pesanan:</h4>
                        <div class="space-y-1 text-xs">
                            @foreach($payment->order->orderItems as $item)
                            <div class="flex justify-between">
                                <div>
                                    <div>{{ $item->menuItem->name ?? 'Item' }}</div>
                                    <div class="text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                                <div>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-1 text-xs">
                        @if($payment->order)
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($payment->order->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if($payment->order->tax_amount > 0)
                        <div class="flex justify-between">
                            <span>Pajak:</span>
                            <span>Rp {{ number_format($payment->order->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($payment->order->service_charge > 0)
                        <div class="flex justify-between">
                            <span>Service:</span>
                            <span>Rp {{ number_format($payment->order->service_charge, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @endif
                        
                        @if($payment->fee > 0)
                        <div class="flex justify-between">
                            <span>Biaya Admin:</span>
                            <span>Rp {{ number_format($payment->fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between font-bold border-t pt-1">
                            <span>Total:</span>
                            <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Metode:</span>
                            <span>
                                @if($payment->payment_method == 'cash') Cash
                                @elseif($payment->payment_method == 'card') Kartu
                                @elseif($payment->payment_method == 'bank_transfer') Transfer
                                @elseif($payment->payment_method == 'e_wallet') E-Wallet
                                @else {{ $payment->payment_method }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="
                                @if($payment->status == 'paid') text-green-600
                                @elseif($payment->status == 'pending') text-yellow-600
                                @else text-red-600
                                @endif">
                                @if($payment->status == 'paid') LUNAS
                                @elseif($payment->status == 'pending') PENDING
                                @else {{ strtoupper($payment->status) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-center text-xs text-gray-500 border-t pt-3">
                        <p>Terima kasih atas kunjungan Anda!</p>
                        <p>Mohon simpan struk ini sebagai bukti pembayaran</p>
                    </div>
                </div>
                
                <div class="mt-4 space-y-2">
                    <button onclick="window.print()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        Cetak Struk
                    </button>
                    <button onclick="downloadReceipt()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Proses Refund</h3>
            <form action="{{ route('admin.payments.refund', $payment) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="refund_amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Refund</label>
                    <input type="number" 
                           id="refund_amount" 
                           name="refund_amount" 
                           value="{{ $payment->amount }}"
                           max="{{ $payment->amount }}"
                           step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Maksimal: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div class="mb-4">
                    <label for="refund_reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Refund</label>
                    <textarea id="refund_reason" 
                              name="refund_reason" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRefundModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Proses Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}

function downloadReceipt() {
    window.location.href = `{{ route('admin.payments.receipt', $payment) }}`;
}

// Close modal when clicking outside
document.getElementById('refundModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRefundModal();
    }
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .sticky, .sticky * {
        visibility: visible;
    }
    .sticky {
        position: static !important;
    }
    button {
        display: none !important;
    }
}
</style>
@endsection
