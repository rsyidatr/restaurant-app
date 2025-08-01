@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Pembayaran</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.payments.report') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Laporan
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
                    <p class="text-sm font-medium text-green-600">Pembayaran Berhasil</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['paid'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Menunggu Pembayaran</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
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
                    <p class="text-sm font-medium text-red-600">Gagal/Dibatalkan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['failed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Pendapatan</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" 
                       id="searchPayment" 
                       placeholder="Cari nomor pesanan..."
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Berhasil</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refund</option>
                </select>
            </div>
            <div>
                <select id="methodFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Kartu</option>
                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="e_wallet" {{ request('method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>
            <div>
                <input type="date" 
                       id="dateFilter"
                       value="{{ request('date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <button onclick="filterPayments()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Payments List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pembayaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Metode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $payment->payment_number ?? $payment->id }}</div>
                            @if($payment->transaction_id)
                                <div class="text-sm text-gray-500">{{ $payment->transaction_id }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->order)
                                <div class="text-sm font-medium text-gray-900">#{{ $payment->order->order_number }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->order->created_at->format('d/m/Y H:i') }}</div>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->order && $payment->order->user)
                                <div class="text-sm font-medium text-gray-900">{{ $payment->order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->order->user->email }}</div>
                            @elseif($payment->order)
                                <div class="text-sm font-medium text-gray-900">{{ $payment->order->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->order->customer_phone ?? '-' }}</div>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->payment_method == 'cash') bg-green-100 text-green-800
                                @elseif($payment->payment_method == 'card') bg-blue-100 text-blue-800
                                @elseif($payment->payment_method == 'bank_transfer') bg-purple-100 text-purple-800
                                @elseif($payment->payment_method == 'e_wallet') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($payment->payment_method == 'cash') Cash
                                @elseif($payment->payment_method == 'card') Kartu
                                @elseif($payment->payment_method == 'bank_transfer') Transfer Bank
                                @elseif($payment->payment_method == 'e_wallet') E-Wallet
                                @else {{ ucfirst($payment->payment_method) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            @if($payment->fee > 0)
                                <div class="text-sm text-gray-500">Fee: Rp {{ number_format($payment->fee, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->status == 'paid') bg-green-100 text-green-800
                                @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->status == 'failed') bg-red-100 text-red-800
                                @elseif($payment->status == 'cancelled') bg-gray-100 text-gray-800
                                @elseif($payment->status == 'refunded') bg-blue-100 text-blue-800
                                @endif">
                                @if($payment->status == 'paid') Berhasil
                                @elseif($payment->status == 'pending') Pending
                                @elseif($payment->status == 'failed') Gagal
                                @elseif($payment->status == 'cancelled') Dibatalkan
                                @elseif($payment->status == 'refunded') Refund
                                @else {{ ucfirst($payment->status) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                            @if($payment->paid_at)
                                <div class="text-xs text-green-600">Dibayar: {{ $payment->paid_at->format('H:i') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.payments.show', $payment) }}" 
                               class="text-blue-600 hover:text-blue-900">Detail</a>
                            
                            @if($payment->status == 'pending')
                                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Verifikasi</button>
                                </form>
                            @endif

                            @if($payment->status == 'paid')
                                <button onclick="showRefundModal({{ $payment->id }})" 
                                        class="text-red-600 hover:text-red-900">Refund</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pembayaran</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada transaksi pembayaran.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @endif
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Proses Refund</h3>
            <form id="refundForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="refund_amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Refund</label>
                    <input type="number" 
                           id="refund_amount" 
                           name="refund_amount" 
                           step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
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
function filterPayments() {
    const search = document.getElementById('searchPayment').value;
    const status = document.getElementById('statusFilter').value;
    const method = document.getElementById('methodFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('status');
    url.searchParams.delete('method');
    url.searchParams.delete('date');
    
    if (search) url.searchParams.set('search', search);
    if (status) url.searchParams.set('status', status);
    if (method) url.searchParams.set('method', method);
    if (date) url.searchParams.set('date', date);
    
    window.location = url;
}

function showRefundModal(paymentId) {
    const modal = document.getElementById('refundModal');
    const form = document.getElementById('refundForm');
    form.action = `/admin/payments/${paymentId}/refund`;
    modal.classList.remove('hidden');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}

// Enter key support for search
document.getElementById('searchPayment').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        filterPayments();
    }
});

// Close modal when clicking outside
document.getElementById('refundModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRefundModal();
    }
});
</script>
@endsection
