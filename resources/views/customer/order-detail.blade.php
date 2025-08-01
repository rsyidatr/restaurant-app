@extends('layouts.customer')

@section('title', 'Detail Pesanan - Restoran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('customer.order-history') }}" 
               class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Riwayat Pesanan
            </a>
            
            <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan</h1>
            <p class="text-gray-600 mt-2">Order #{{ $order->order_number }}</p>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-orange-600 text-white px-6 py-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-bold">Order #{{ $order->order_number }}</h2>
                        <p class="text-orange-100">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-2 mt-2 md:mt-0">
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                   {{ $order->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                                      ($order->status === 'processing' ? 'bg-blue-200 text-blue-800' : 
                                      ($order->status === 'ready' ? 'bg-green-200 text-green-800' : 
                                      ($order->status === 'completed' ? 'bg-gray-200 text-gray-800' : 
                                      'bg-red-200 text-red-800'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                   {{ $order->payment_status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                                      'bg-green-200 text-green-800' }}">
                            {{ $order->payment_status === 'pending' ? 'Belum Bayar' : 'Sudah Bayar' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Info -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Informasi Pelanggan</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-semibold ml-2">{{ $order->customer_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Telepon:</span>
                                <span class="font-semibold ml-2">{{ $order->customer_phone }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Meja:</span>
                                <span class="font-semibold ml-2">
                                    @if($order->table)
                                        Meja {{ $order->table->table_number }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Informasi Pembayaran</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-600">Metode:</span>
                                <span class="font-semibold ml-2">{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span>
                                <span class="font-semibold ml-2 {{ $order->payment_status === 'pending' ? 'text-yellow-600' : 'text-green-600' }}">
                                    {{ $order->payment_status === 'pending' ? 'Belum Bayar' : 'Sudah Bayar' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-xl text-orange-600 ml-2">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Catatan</h3>
                    <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Detail Pesanan</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Qty
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $item->image }}" alt="{{ $item->menu_name }}" 
                                         class="w-16 h-16 object-cover rounded-lg mr-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->menu_name }}</div>
                                        @if($item->notes)
                                        <div class="text-sm text-gray-500">Catatan: {{ $item->notes }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                Total Pembayaran:
                            </td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-orange-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('checkout.receipt', $order->id) }}" 
               class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors font-semibold text-center">
                <i class="fas fa-receipt mr-2"></i>
                Cetak Struk
            </a>
            
            <a href="{{ route('customer.menu') }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold text-center">
                <i class="fas fa-shopping-cart mr-2"></i>
                Pesan Lagi
            </a>
            
            <a href="{{ route('customer.order-history') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold text-center">
                <i class="fas fa-list mr-2"></i>
                Lihat Semua Riwayat
            </a>
        </div>
    </div>
</div>
@endsection
