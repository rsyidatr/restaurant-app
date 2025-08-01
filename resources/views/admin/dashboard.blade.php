@extends('layouts.admin_clean')

@section('title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</h3>
                <p class="text-sm text-gray-500">Total Users</p>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</h3>
                <p class="text-sm text-gray-500">Total Orders</p>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-sm text-gray-500">Total Revenue</p>
            </div>
        </div>
    </div>
    
    <!-- Today's Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-day text-white text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($todayOrders) }}</h3>
                <p class="text-sm text-gray-500">Today's Orders</p>
                <p class="text-xs text-gray-400">Revenue: Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
        </div>
        <div class="p-6">
            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">#{{ $order->id }} - {{ $order->user->name ?? 'Guest' }}</h4>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($order->order_type) }}
                                @if($order->table)
                                    - Table {{ $order->table->table_number }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        View all orders →
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-gray-400 text-3xl mb-4"></i>
                    <p class="text-gray-500">No orders yet</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Table Status -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Table Status</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Available</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $tableStats['available'] }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Reserved</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $tableStats['reserved'] }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Occupied</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $tableStats['occupied'] }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Cleaning</span>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $tableStats['cleaning'] }}</span>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('admin.tables.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Manage tables →
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Popular Menu Items -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Most Popular Menu Items</h3>
    </div>
    <div class="p-6">
        @if($popularMenus->count() > 0)
            <div class="space-y-4">
                @foreach($popularMenus as $menu)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $menu->name }}</h4>
                        <p class="text-sm text-gray-500">Total ordered: {{ $menu->total_ordered }} times</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-blue-600">{{ $menu->total_ordered }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-utensils text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-500">No menu data available</p>
            </div>
        @endif
    </div>
</div>
@endsection
