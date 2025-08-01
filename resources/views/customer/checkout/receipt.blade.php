@extends('layouts.customer')

@section('title', 'Receipt - Restoran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-semibold">Pesanan berhasil dibuat!</span>
            </div>
        </div>

        <!-- Receipt -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden" id="receipt">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <div class="text-center">
                    <h1 class="text-2xl font-bold">STRUK PESANAN</h1>
                    <p class="text-blue-100">{{ config('app.name', 'Restoran') }}</p>
                </div>
            </div>

            <!-- Order Info -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Informasi Pesanan</h3>
                        <p class="text-sm text-gray-600">Nomor Order: <span class="font-semibold text-gray-900">{{ $order->order_number }}</span></p>
                        <p class="text-sm text-gray-600">Tanggal: <span class="font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
                        <p class="text-sm text-gray-600">Status: 
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                         {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            ($order->status === 'preparing' ? 'bg-blue-100 text-blue-800' : 
                                            ($order->status === 'ready' ? 'bg-green-100 text-green-800' : 
                                            'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Informasi Pelanggan</h3>
                        <p class="text-sm text-gray-600">Nama: <span class="font-semibold text-gray-900">{{ $order->customer_name }}</span></p>
                        <p class="text-sm text-gray-600">Telepon: <span class="font-semibold text-gray-900">{{ $order->customer_phone }}</span></p>
                        <p class="text-sm text-gray-600">Meja: <span class="font-semibold text-gray-900">{{ $order->table->table_number }}</span></p>
                        <p class="text-sm text-gray-600">Pembayaran: <span class="font-semibold text-gray-900">{{ ucfirst($order->payment_method) }}</span></p>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="mt-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Catatan</h3>
                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4">
                <h3 class="font-semibold text-gray-900 mb-4">Detail Pesanan</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 text-sm font-semibold text-gray-900">Item</th>
                                <th class="text-center py-2 text-sm font-semibold text-gray-900">Qty</th>
                                <th class="text-right py-2 text-sm font-semibold text-gray-900">Harga</th>
                                <th class="text-right py-2 text-sm font-semibold text-gray-900">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr class="border-b border-gray-100">
                                <td class="py-3">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $item->image }}" alt="{{ $item->menu_name }}" 
                                             class="w-12 h-12 object-cover rounded">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->menu_name }}</p>
                                            @if($item->notes)
                                            <p class="text-xs text-gray-500">{{ $item->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-3 text-right font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">Total Pembayaran:</span>
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-100 text-center">
                <p class="text-sm text-gray-600">Terima kasih atas pesanan Anda!</p>
                <p class="text-xs text-gray-500 mt-1">Simpan struk ini sebagai bukti pesanan</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.print()" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Struk
            </button>
            
            <a href="{{ route('customer.menu') }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Pesan Lagi
            </a>
            
            <a href="{{ route('home') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Home
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    
    #receipt, #receipt * {
        visibility: visible;
    }
    
    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .no-print {
        display: none !important;
    }
}
</style>
@endpush
