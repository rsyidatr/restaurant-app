@extends('layouts.customer')

@section('title', 'Riwayat Pesanan - Restoran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Pesanan</h1>
            <p class="text-gray-600 mt-2">Lihat semua pesanan yang pernah Anda buat</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                                   {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                      ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                                      ($order->status === 'ready' ? 'bg-green-100 text-green-800' : 
                                                      ($order->status === 'completed' ? 'bg-gray-100 text-gray-800' : 
                                                      'bg-red-100 text-red-800'))) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                                   {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                      'bg-green-100 text-green-800' }}">
                                            {{ $order->payment_status === 'pending' ? 'Belum Bayar' : 'Sudah Bayar' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Nama Pelanggan</p>
                                        <p class="font-semibold">{{ $order->customer_name }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600">Meja</p>
                                        <p class="font-semibold">
                                            @if($order->table)
                                                Meja {{ $order->table->table_number }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                                        <p class="font-semibold">{{ ucfirst($order->payment_method) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Order Items Preview -->
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Item Pesanan ({{ $order->orderItems->count() }} item)</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($order->orderItems->take(3) as $item)
                                            <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                {{ $item->quantity }}x {{ $item->menu_name }}
                                            </span>
                                        @endforeach
                                        
                                        @if($order->orderItems->count() > 3)
                                            <span class="inline-block bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">
                                                +{{ $order->orderItems->count() - 3 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col lg:items-end space-y-3">
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                                    <p class="text-2xl font-bold text-orange-600">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('customer.order-history.show', $order->id) }}" 
                                       class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat Detail
                                    </a>
                                    
                                    <a href="{{ route('checkout.receipt', $order->id) }}" 
                                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-center">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Cetak Struk
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Riwayat Pesanan</h3>
                <p class="text-gray-600 mb-6">
                    Anda belum pernah melakukan pesanan. Mulai jelajahi menu kami dan buat pesanan pertama Anda!
                </p>
                <a href="{{ route('customer.menu') }}" 
                   class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-book-open mr-2"></i>
                    Lihat Menu
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
